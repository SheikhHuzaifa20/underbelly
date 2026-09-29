<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\OrderStatusLogs;
use App\Models\Address;
use App\Models\AddressAuditLog;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $pendingOrdersCount = Orders::where('order_status', 'pending')->count();
        $completedOrdersCount = Orders::where('order_status', 'delivered')->count();
        $canceledOrdersCount = Orders::where('order_status', 'canceled')->count();
        $returnedOrdersCount = Orders::where('order_status', 'returned')->count();
        return view('admin.orders.index', compact(
            'pendingOrdersCount',
            'completedOrdersCount',
            'canceledOrdersCount',
            'returnedOrdersCount'
        ));
    }

    public function getData(Request $request)
    {
        $query = Orders::with('user')->orderBy('id', 'desc');

        // Status filter
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Date filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        return DataTables::of($query)

            ->addColumn('customer_name', function ($row) {
                return $row->user->name ?? '-';
            })

            ->addColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d M, Y h:i A')
                    : '-';
            })

            ->addColumn('order_status', function ($row) {

                $status = $row->order_status;

                $badge = match ($status) {
                    'delivered'  => 'success',
                    'pending'    => 'warning',
                    'canceled'   => 'danger',
                    'returned'   => 'info',
                    'in_process' => 'primary',
                    'shipped'    => 'secondary',
                    default      => 'secondary',
                };

                $label = ucfirst(str_replace('_', ' ', $status));

                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-' . $badge . ' dropdown-toggle"
                                type="button"
                                data-toggle="dropdown">
                            ' . $label . '
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item changeStatus" data-id="'.$row->id.'" data-status="pending">Pending</a>
                            <a class="dropdown-item changeStatus" data-id="'.$row->id.'" data-status="in_process">In Process</a>
                            <a class="dropdown-item changeStatus" data-id="'.$row->id.'" data-status="shipped">Shipped</a>
                            <a class="dropdown-item changeStatus" data-id="'.$row->id.'" data-status="delivered">Delivered</a>
                            <a class="dropdown-item changeStatus" data-id="'.$row->id.'" data-status="returned">Returned</a>
                            <a class="dropdown-item changeStatus text-danger" data-id="'.$row->id.'" data-status="canceled">Canceled</a>
                        </div>
                    </div>
                ';
            })

            ->addColumn('payment_method', function ($row) {
                return ucfirst($row->payment_method ?? '-');
            })

            ->addColumn('action', function ($row) {
                return '
                    <a href="'.route('admin.orders.show', $row->id).'" class="btn btn-sm btn-primary">View</a>
                    <a href="'.route('admin.orders.invoice', $row->id).'" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="fas fa-file-invoice"></i> Invoice</a>
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger deleteOrders" title="Delete Orders">Delete</button>
                ';
            })

            ->rawColumns(['order_status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $order = Orders::with(['order_products.product', 'status_logs', 'shippingAddress.auditLogs.user', 'billingAddress.auditLogs.user'])->findOrFail($id);
        $shippingAddress = $order->shippingAddress;

        $statusMap = [
            'pending'     => ['class' => 'warning', 'icon' => 'clock'],
            'in_process'  => ['class' => 'primary', 'icon' => 'spinner'],
            'shipped'     => ['class' => 'secondary', 'icon' => 'truck'],
            'delivered'   => ['class' => 'success', 'icon' => 'check-circle'],
            'canceled'    => ['class' => 'danger', 'icon' => 'times-circle'],
            'returned'    => ['class' => 'info', 'icon' => 'undo'],
        ];

        $status = $order->order_status;
        $badge  = $statusMap[$status] ?? ['class' => 'dark', 'icon' => 'question'];

        return view('admin.orders.show', compact('order', 'shippingAddress', 'badge', 'status', 'statusMap'));
    }

    public function edit($id)
    {
        $order = Orders::findOrFail($id);

        return view('admin.orders.edit', compact('order'));
    }

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        $order->delete();
        if($order->order_products){
            foreach($order->order_products as $product){
                $product->delete();
            }
        }

        log_activity('delete', Orders::class, $order->id, "Deleted order {$order->id}");
        return response()->json(['success' => 'Order deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No items selected.'], 400);
        }

        Orders::whereIn('id', $ids)->delete();
        OrderProducts::whereIn('orders_id', $ids)->delete();

        log_activity('bulk_delete', Orders::class, null, 'Bulk deleted orders: ' . implode(',', $ids), ['ids' => $ids]);

        return response()->json(['success' => 'Selected orders deleted successfully.']);
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_process,shipped,delivered,returned,canceled'
        ]);

        $order = Orders::findOrFail($id);

        $allowedFlow = [
            'pending'    => ['in_process', 'canceled'],
            'in_process' => ['shipped', 'canceled'],
            'shipped'    => ['delivered', 'returned'],
        ];

        $current = $order->order_status;

        if (!in_array($request->status, $allowedFlow[$current] ?? [])) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid status transition'
            ], 422);
        }

        // Update order status
        $order->order_status = $request->status;
        $order->save();

        log_activity('change_status', Orders::class, $order->id, "Changed order status to {$request->status}");

        // Log status
        OrderStatusLogs::create([
            'order_id' => $order->id,
            'status'   => $request->status
        ]);

        $order->load('status_logs');

        // Badge UI mapping
        $badgeMap = [
            'pending'     => ['class' => 'warning', 'icon' => 'clock'],
            'in_process'  => ['class' => 'primary', 'icon' => 'spinner'],
            'shipped'     => ['class' => 'secondary', 'icon' => 'truck'],
            'delivered'   => ['class' => 'success', 'icon' => 'check-circle'],
            'canceled'    => ['class' => 'danger', 'icon' => 'times-circle'],
            'returned'    => ['class' => 'info', 'icon' => 'undo'],
        ];

        $timelineHtml = view(
            'admin.orders.partials.timeline',
            compact('order')
        )->render();

        return response()->json([
            'success'  => true,
            'status'   => $request->status,
            'badge'    => $badgeMap[$request->status],
            'timeline' => $timelineHtml
        ]);
    }

    public function updateAddress(Request $request, Address $address)
    {
        $old = $address->toArray();

        $address->update($request->only([
            'recipient','street','city','state','zip','country'
        ]));

        AddressAuditLog::create([
            'address_id' => $address->id,
            'user_id'    => auth()->id(),
            'old_data'   => $old,
            'new_data'   => $address->toArray(),
        ]);

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    public function invoice(Orders $order)
    {
        $order->load([
            'order_products.product',
            'shippingAddress',
            'billingAddress',
            'user'
        ]);

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'))
                  ->setPaper('a4');

        return $pdf->stream("invoice-order-{$order->id}.pdf");
        // OR ->download()
    }
}
