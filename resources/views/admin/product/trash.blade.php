@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Trashed Book</h3>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <button id="bulkRestore" class="btn btn-success mb-1">Restore Selected</button>
            <button id="bulkForceDelete" class="btn btn-danger mb-1 ml-1 mr-1">Delete Permanently</button>
            <a class="btn btn-info mb-1" href="{{ route('admin.product.index') }}">Back to Active Book</a>
        </div>
    </div>
</div>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deleted Book</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th class="select-all-col"><input type="checkbox" id="selectAll"></th>
                                    <th>ID</th>
                                    <th>Book Name</th>
                                    {{-- <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>SKU</th> --}}
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Deleted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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
$(function() {
    CRUDManager.init({
        tableSelector: '.yajra-datatable',
        entity: 'product',
        routes: {
            data: "{{ route('admin.product.trash.data') }}",
            restore: "{{ route('admin.product.restore', ':id') }}",
            forceDelete: "{{ route('admin.product.forceDelete', ':id') }}",
            bulkRestore: "{{ route('admin.product.bulkRestore') }}",
            bulkForceDelete: "{{ route('admin.product.bulkForceDelete') }}"
        },
        columns: [
            {
                data: 'id',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="rowCheckbox" value="${data}">`;
                }
            },
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            // {data: 'category', name: 'category'},
            // {data: 'sub_category', name: 'sub_category'},
            // {data: 'sku', name: 'sku'},
            {data: 'base_price', name: 'base_price'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'deleted_at', name: 'deleted_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
