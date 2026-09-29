<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Traits\FileUploadTrait;

class TestimonialController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        return view('admin.testimonial.index');
    }

    public function getData(Request $request)
    {
        $query = Testimonial::orderBy('sort_order', 'asc');

        // Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        return DataTables::of($query)
            ->addColumn('image', function ($row) {
                if (!$row->image) {
                    return '<span class="text-muted">No Image</span>';
                }
                // Lazy loading image
                return '<img src="'.asset($row->image).'" class="lazy-load" width="120" />';
            })
            ->addColumn('rating', function ($row) {
                if (!$row->rating) {
                    return '<span class="text-muted">No Rating</span>';
                }
                // Display as stars (★)
                return str_repeat('★', $row->rating) . str_repeat('☆', 5 - $row->rating);
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" class="toggleTestimonialStatus" data-id="' . $row->id . '" ' . $checked . '>
                        <span class="slider round" title="Click to toggle status"></span>
                    </label>
                ';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y h:i A') : '-';
            })
            ->addColumn('action', function ($row) {
                $actions = '';
                if (auth()->user()->hasPermission('edit_testimonial')) {
                    $actions .= '<a href="'.url('admin/testimonial/'.$row->id.'/edit').'"
                                    class="btn btn-sm btn-info"
                                    title="Edit Testimonial">
                                    <i class="la la-pencil"></i>
                                  </a> ';
                }
                if (auth()->user()->hasPermission('delete_testimonial')) {
                    $actions .= '<button class="btn btn-sm btn-danger deleteTestimonial"
                                    data-id="'.$row->id.'"
                                    title="Delete Testimonial">
                                    <i class="la la-trash"></i>
                                  </button>';
                }
                return $actions ?: '<span class="text-muted">No actions</span>';
            })
            ->rawColumns(['image', 'rating', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function store(StoreTestimonialRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/testimonial/', 'testimonial');
        }

        $item = Testimonial::create($data);

        log_activity('create', Testimonial::class, $item->id, 'Created new testimonial: ' . ($item->title ?? 'N/A'));

        return redirect()->route('admin.testimonial.index')
            ->with('message', 'Testimonial added successfully!');
    }

    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonial.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteFile($testimonial->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/testimonial/', 'testimonial');
        }

        $oldData = $testimonial->toArray();
        $testimonial->update($data);

        log_activity('update', Testimonial::class, $testimonial->id, 'Updated testimonial', [
            'before' => $oldData,
            'after' => $testimonial->toArray()
        ]);

        return redirect()->route('admin.testimonial.index')->with('message', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        log_activity('delete', Testimonial::class, $testimonial->id, "Deleted testimonial");
        return response()->json(['success' => 'Testimonial deleted successfully.']);
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $oldStatus = $testimonial->status;
        $testimonial->status = !$oldStatus;
        $testimonial->save();

        log_activity('status_toggle', Testimonial::class, $testimonial->id,
            'Toggled status for testimonials no. '.$testimonial->id.' from ' . ($oldStatus ? 'Active' : 'Inactive') . ' to ' . ($testimonial->status ? 'Active' : 'Inactive'));

        return response()->json([
            'success' => true,
            'status' => $testimonial->status ? 'Active' : 'Inactive',
        ]);
    }

    public function trash()
    {
        return view('admin.testimonial.trash');
    }

    public function getTrashedData(Request $request)
    {
        $items = Testimonial::onlyTrashed()->orderByDesc('id')->get();

        return DataTables::of($items)
            ->addColumn('checkbox', fn($row) =>
                '<input type="checkbox" class="rowCheckbox" value="'.$row->id.'">'
            )
            ->addColumn('image', function($row) {
                return $row->image
                    ? '<img src="'.asset($row->image).'" class="lazy-load" width="120" />'
                    : '<span class="text-muted">No Image</span>';
            })
            ->addColumn('rating', function($row) {
                if (!$row->rating) {
                    return '<span class="text-muted">No Rating</span>';
                }
                return str_repeat('★', $row->rating) . str_repeat('☆', 5 - $row->rating);
            })
            ->addColumn('action', function($row) {
                $restore = '<button class="btn btn-sm btn-success restoreTestimonial" data-id="'.$row->id.'" title="Restore"><i class="la la-refresh"></i></button>';
                $delete = '<button class="btn btn-sm btn-danger forceDeleteTestimonial" data-id="'.$row->id.'" title="Delete Permanently"><i class="la la-trash"></i></button>';
                return $restore . ' ' . $delete;
            })
            ->rawColumns(['checkbox', 'image', 'rating', 'action'])
            ->make(true);
    }

    public function restore($id)
    {
        $item = Testimonial::withTrashed()->findOrFail($id);
        $item->restore();
        log_activity('restore', Testimonial::class, $id, "Restored testimonial");
        return response()->json(['success' => 'Testimonial restored successfully!']);
    }

    public function forceDelete($id)
    {
        $item = Testimonial::withTrashed()->findOrFail($id);
        $this->deleteFile($item->image);
        $item->forceDelete();

        log_activity('force_delete', Testimonial::class, $id, "Permanently deleted testimonial");
        return response()->json(['success' => 'Testimonial permanently deleted.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        Testimonial::whereIn('id', $ids)->delete();
        log_activity('bulk_delete', Testimonial::class, null, 'Bulk delete', ['ids' => $ids]);
        return response()->json(['success' => 'Selected testimonial deleted successfully.']);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        Testimonial::withTrashed()->whereIn('id', $ids)->restore();
        log_activity('bulk_restore', Testimonial::class, null, 'Bulk restore', ['ids' => $ids]);
        return response()->json(['success' => 'Selected testimonial restored successfully.']);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        $items = Testimonial::withTrashed()->whereIn('id', $ids)->get();
        foreach ($items as $item) {
            $this->deleteFile($item->image);
            $item->forceDelete();
        }

        log_activity('bulk_force_delete', Testimonial::class, null, 'Bulk permanently delete', ['ids' => $ids]);
        return response()->json(['success' => 'Selected testimonial permanently deleted.']);
    }

    public function sort(Request $request)
    {
        $order = $request->input('order', []);
        if (!is_array($order) || empty($order)) {
            return response()->json(['success' => false, 'message' => 'No order data received'], 400);
        }

        foreach ($order as $item) {
            $pos = $item['position'] ?? $item['newPosition'] ?? null;
            $id  = $item['id'] ?? null;
            if ($id && $pos !== null) {
                Testimonial::where('id', $id)->update(['sort_order' => (int)$pos]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
}
