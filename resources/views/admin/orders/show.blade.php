@extends('layouts.app')
@push('before-css')
    <style>
        .product-table img {
            object-fit: cover;
            border: 1px solid #eee;
        }

        .shipping-timeline.enhanced {
            list-style: none;
            padding-left: 0;
            position: relative;
        }

        .shipping-timeline.enhanced li {
            position: relative;
            padding-left: 50px;
            padding-bottom: 35px;
            cursor: pointer;
        }

        .shipping-timeline.enhanced li:last-child {
            padding-bottom: 0;
        }

        /* Vertical line */
        .shipping-timeline.enhanced li::before {
            content: '';
            position: absolute;
            left: 22px;
            top: 0;
            width: 2px;
            height: 100%;
            background: #e5e7eb;
        }

        .shipping-timeline.enhanced li:last-child::before {
            display: none;
        }

        /* Icon */
        .timeline-icon {
            position: absolute;
            left: 10px;
            top: 0;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
            transition: transform 0.3s ease;
        }

        /* Status Colors */
        li.success .timeline-icon {
            background: #22c55e;
        }

        li.warning .timeline-icon {
            background: #f59e0b;
        }

        li.info .timeline-icon {
            background: #3b82f6;
        }

        li.pending .timeline-icon {
            background: #9ca3af;
        }

        /* Animation */
        li.active .timeline-icon {
            box-shadow: 0 0 0 6px rgba(99, 102, 241, 0.12);
            animation: pulse 1.6s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, .3);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(99, 102, 241, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }
        }

        /* Text */
        .timeline-content p {
            margin: 4px 0 0;
            font-size: 14px;
            color: #6b7280;
        }

        .timeline-time {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Click effect */
        .shipping-timeline li:hover .timeline-icon {
            transform: scale(1.1);
        }

        li.active .timeline-icon {
            animation: pulse 1.6s infinite;
        }

        .address-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .address-card:hover {
            border-color: #7367f0;
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">View Order</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active"><a href="{{url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Ecommerce</li>
                        <li class="breadcrumb-item active">Orders</li>
                        <li class="breadcrumb-item active">View Order</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="btn-group float-md-right">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown" aria-expanded="false">
                    Update Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                        <li><a class="dropdown-item changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="pending">Pending</a></li>
                        <li><a class="dropdown-item changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="in_process">In Process</a></li>
                        <li><a class="dropdown-item changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="shipped">Shipped</a></li>
                        <li><a class="dropdown-item changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="delivered">Delivered</a></li>
                        <li><a class="dropdown-item changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="returned">Returned</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger changeStatus" href="javascript:void(0)" data-id={{ $order->id }} data-status="canceled">Canceled</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        {{-- Top Row --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            {{-- Order Title --}}
                            <div>
                                <h4 class="fw-bold mb-1">
                                    Order #{{ $order->id }}
                                </h4>

                                <div class="text-muted small">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                    <span class="mx-2">•</span>
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>

                            {{-- Status + Actions --}}
                            <div class="d-flex align-items-center mt-2 mt-md-0">

                                <span class="badge badge-{{ $badge['class'] }}
                                            d-inline-flex align-items-center
                                            px-1 py-1 mr-2"
                                    style="height:32px;" id="show-status">
                                    <i class="fas fa-{{ $badge['icon'] }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>

                                <a class="btn btn-sm btn-outline-secondary
                                        d-inline-flex align-items-center"
                                style="height:32px;"
                                href="{{ route('admin.orders.invoice', $order->id) }}"
                                target="_blank">
                                    <i class="fas fa-file-invoice mr-1"></i>
                                    Invoice
                                </a>

                            </div>

                        </div>

                        {{-- Meta Info Row --}}
                        <div class="row mt-3 text-sm">

                            <div class="col-md-3">
                                <div class="text-muted">Payment</div>
                                <div class="fw-semibold">
                                    {{ ucfirst($order->payment_method) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="text-muted">Total</div>
                                <div class="fw-semibold">
                                    ${{ number_format($order->order_products->sum(fn($op) => ($op->product?->base_price ?? 0) * $op->order_products_qty) + ($order->discount_amount ?? 0.00) + ($order->shipping_tax ?? 0.00), 2) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="text-muted">Items</div>
                                <div class="fw-semibold">
                                    {{ $order->order_products->sum('order_products_qty') }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="text-muted">Customer</div>
                                <div class="fw-semibold">
                                    {{ $order->user->name ?? 'Guest' }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex m-b-10 no-block">
                            <h5 class="card-title m-b-0 align-self-center text-uppercase">Order Details</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table product-table color-table primary-table">
                                <thead>
                                    <tr>

                                        <th>ID </th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->order_products as $orderProduct)
                                        <tr>
                                            <td>{{ $orderProduct->product?->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/products/oneplus.png"
                                                        alt="Product Name" class="rounded me-2" width="40" height="40">
                                                    <span>{{ $orderProduct->product?->name }}</span>
                                                </div>
                                            </td>
                                            <td>${{ $orderProduct->product?->price ?? 0 }}</td>
                                            <td>{{ $orderProduct->order_products_qty }}</td>
                                            <td>${{ ($orderProduct->product?->price ?? 0) * $orderProduct->order_products_qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row justify-content-end mt-4">
                                <div class="col-md-5">
                                    <table class="table table-borderless text-end">
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold">Subtotal:</td>
                                                <td>${{ $order->order_products->sum(fn($op) => ($op->product?->price ?? 0) * $op->order_products_qty) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Tax:</td>
                                                <td>${{ $order->shipping_tax ?? 0.00 }}</td>
                                            </tr>
                                            <tr class="border-top">
                                                <td class="fw-bold fs-5">Total:</td>
                                                <td class="fw-bold fs-5">${{ $order->order_products->sum(fn($op) => ($op->product?->base_price ?? 0) * $op->order_products_qty) + ($order->discount_amount ?? 0.00) + ($order->shipping_tax ?? 0.00) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Shipping activity</h5>

                        <div id="order-timeline">
                            @include('admin.orders.partials.timeline', ['order' => $order])
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card mb-3">
                    <div class="card-body">

                        <h5 class="card-title text-uppercase">Customer Details</h5>

                        <div class="d-flex align-items-center">
                            <img src="{{ $order->user->pic ?? asset('assets/imgs/default.png') }}"
                                class="rounded-circle me-3" width="48">

                            <div>
                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                <div class="text-muted small">
                                    Customer ID: #{{ $order->user->id }}
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-uppercase text-muted">Contact Info</h6>

                        <div class="small">
                            <div>
                                <span class="text-muted">Email:</span>
                                {{ $order->user->email }}
                            </div>
                            <div>
                                <span class="text-muted">Mobile:</span>
                                {{ $order->user->phone ?? 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>

                <x-address-card
                    title="Shipping Address"
                    :address="$order->shippingAddress"
                />

                <x-address-card
                    title="Billing Address"
                    :address="$order->billingAddress"
                />

                <div class="card mt-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Address Change History</h6>

                        @forelse($shippingAddress->auditLogs as $log)
                            <div class="small border-bottom py-2">
                                <div>
                                    <strong>Updated by:</strong>
                                    {{ $log->user->name ?? 'System' }}
                                </div>

                                <div class="text-muted">
                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                    ({{ $log->created_at->diffForHumans() }})
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No changes yet</p>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Payment Method</h6>

                        @php
                            $payment = payment_method_info($order->payment_method);
                        @endphp

                        <div class="d-flex align-items-center gap-2 mt-2">
                            <img src="{{ asset($payment['logo']) }}"
                                alt="{{ $payment['name'] }}"
                                height="22">

                            <span class="fw-semibold">
                                {{ $payment['name'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>

    <div class="modal fade" id="editAddressModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Address</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="addressForm">
                        @csrf
                        <input type="hidden" id="address_id">

                        <input class="form-control mb-2" id="recipient" placeholder="Recipient">
                        <input class="form-control mb-2" id="street" placeholder="Street">
                        <input class="form-control mb-2" id="city" placeholder="City">
                        <input class="form-control mb-2" id="state" placeholder="State">
                        <input class="form-control mb-2" id="zip" placeholder="ZIP">
                        <input class="form-control mb-2" id="country" placeholder="Country">
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveAddress">Save</button>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('js')
    <script>
        $(document).ready(function () {

            $(document).on('click', '.shipping-timeline li', function () {
                $(this).find('p').slideToggle(200);
            });

            $(document).on('click', '.changeStatus', function (e) {
                e.preventDefault();

                let orderId = $(this).data('id');
                let status  = $(this).data('status');

                $.ajax({
                    url: "{{ route('admin.order.changeStatus', ':id') }}".replace(':id', orderId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function (response) {

                        if (response.success) {

                            // Format text
                            let formattedStatus = status.replace('_', ' ');
                            formattedStatus = formattedStatus.charAt(0).toUpperCase() + formattedStatus.slice(1);

                            // Update badge
                            let badge = $('#show-status');

                            badge
                                .removeClass()
                                .addClass(
                                    'badge badge-' + response.badge.class +
                                    ' d-inline-flex align-items-center px-1 py-1 mr-2'
                                )
                                .css('height', '32px')
                                .html(
                                    '<i class="fas fa-' + response.badge.icon + ' mr-1"></i>' +
                                    formattedStatus
                                );

                            // Update timeline
                            $('#order-timeline').html(response.timeline);
                        }
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Status update failed');
                    }
                });
            });

            $(document).on('click', '.edit-address', function () {
                let address = $(this).data('address');

                $('#address_id').val(address.id);
                $('#recipient').val(address.recipient);
                $('#street').val(address.street);
                $('#city').val(address.city);
                $('#state').val(address.state);
                $('#zip').val(address.zip);
                $('#country').val(address.country);

                $('#editAddressModal').modal('show');
            });

            $('#saveAddress').on('click', function () {

                let id = $('#address_id').val();
                let url = "{{ route('admin.address.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: $('input[name=_token]').val(),
                        recipient: $('#recipient').val(),
                        street: $('#street').val(),
                        city: $('#city').val(),
                        state: $('#state').val(),
                        zip: $('#zip').val(),
                        country: $('#country').val(),
                    },
                    success: function () {
                        location.reload(); // simple & safe
                    }
                });
            });
        });
    </script>

    @if(in_array($order->order_status, ['delivered','canceled','returned']))
        <script>
            $('.changeStatus').addClass('disabled');
        </script>
    @endif
@endpush
