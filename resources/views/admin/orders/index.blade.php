@extends('layouts.app')

@push('before-css')
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Orders</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item active">Ecommerce</li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            @canAccess('delete_orders')
                <button id="bulkDelete" class="btn btn-danger mr-1 mb-1">Delete Selected</button>
            @endcanAccess
        </div>
    </div>
</div>


<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Orders List</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="custom-stat-card total">
                                <div class="icon">
                                    <i class="fa-solid fa-calendar"></i>
                                </div>
                                <div class="details">
                                    <h3 id="pendingPayments">{{ $pendingOrdersCount ?? 0 }}</h3>
                                    <p>Pending Payments</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="custom-stat-card pending">
                                <div class="icon">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="details">
                                    <h3 id="completedPayments">{{ $completedOrdersCount ?? 0 }}</h3>
                                    <p>Completed</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="custom-stat-card duplicate">
                                <div class="icon">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="details">
                                    <h3 id="refundPayments">{{ $returnedOrdersCount ?? 0 }}</h3>
                                    <p>Refund</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="custom-stat-card verified">
                                <div class="icon">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </div>
                                <div class="details">
                                    <h3 id="failedPayments">{{ $canceledOrdersCount ?? 0 }}</h3>
                                    <p>Failed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-end">
                        <div class="col-md-2">
                            <label>Status</label>
                            <select id="statusFilter" class="form-control">
                                <option value="">All</option>
                                <option value="delivered">Delivered</option>
                                <option value="pending">Pending</option>
                                <option value="canceled">Canceled</option>
                                <option value="in_process">In Process</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Date From</label>
                            <input type="date" id="fromDate" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Date To</label>
                            <input type="date" id="toDate" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <button id="resetFilters" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th class="select-all-col"><input type="checkbox" id="selectAll"></th>
                                    <th>S.No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
$(function () {
    CRUDManager.init({
        tableSelector: '.yajra-datatable',
        entity: 'orders',
        routes: {
            data: "{{ route('admin.orders.data') }}",
            delete: "{{ route('admin.orders.destroy', ':id') }}",
            bulkDelete: "{{ route('admin.orders.bulkDelete') }}"
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `<input type="checkbox" class="rowCheckbox" value="${data}">`;
                }
            },
            {
                data: null,
                name: 'id',
                orderable: true,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'order_status', name: 'order_status' },
            { data: 'payment_method', name: 'payment_method' },
            {
                data: 'action',
                orderable: false,
                searchable: false
            }
        ],
        extraFilters: function () {
            return {
                status: $('#statusFilter').val(),
                from_date: $('#fromDate').val(),
                to_date: $('#toDate').val(),
            };
        }
    });

    // Reload on filter change
    $('#statusFilter, #fromDate, #toDate').on('change', function () {
        $('.yajra-datatable').DataTable().ajax.reload();
    });

    // Reset filters
    $('#resetFilters').on('click', function () {
        $('#statusFilter').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        $('.yajra-datatable').DataTable().ajax.reload();
    });

    $(document).on('click', '.changeStatus', function () {
        let orderId = $(this).data('id');
        let status  = $(this).data('status');

        $.ajax({
            url: "{{ route('admin.order.changeStatus', ':id') }}".replace(':id', orderId),
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function (res) {
                $('.yajra-datatable').DataTable().ajax.reload(null, false);
            }
        });
    });
});
</script>
@endpush
