<ul class="shipping-timeline enhanced">
@foreach($order->status_logs as $log)

    @php
        $map = [
            'order_placed' => ['success', 'fa-receipt', 'Order Placed'],
            'pending'      => ['pending', 'fa-clock', 'Pending'],
            'in_process'   => ['info', 'fa-box-open', 'Processing'],
            'shipped'      => ['info', 'fa-truck-fast', 'Shipped'],
            'delivered'    => ['success', 'fa-circle-check', 'Delivered'],
            'returned'     => ['warning', 'fa-rotate-left', 'Returned'],
            'canceled'     => ['danger', 'fa-circle-xmark', 'Canceled'],
        ];

        [$class, $icon, $label] = $map[$log->status] ?? ['pending','fa-info','Updated'];

        $active = $loop->last ? 'active' : '';
    @endphp

    <li class="{{ $active }} {{ $class }}">
        <span class="timeline-icon">
            <i class="fas {{ $icon }}"></i>
        </span>

        <div class="timeline-content">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $label }}</strong>
                <span class="timeline-time">
                    {{ $log->created_at->format('d M Y, h:i A') }}
                </span>
            </div>

            <p class="small text-muted mt-1">
                @switch($log->status)
                    @case('order_placed')
                        Your order has been placed successfully.
                        @break
                    @case('pending')
                        Order is awaiting processing.
                        @break
                    @case('in_process')
                        Order is being prepared.
                        @break
                    @case('shipped')
                        Order has been shipped.
                        @break
                    @case('delivered')
                        Order delivered successfully.
                        @break
                    @case('returned')
                        Order returned by customer.
                        @break
                    @case('canceled')
                        Order was canceled.
                        @break
                @endswitch
            </p>
        </div>
    </li>

@endforeach
</ul>
