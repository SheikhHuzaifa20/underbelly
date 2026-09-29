@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Book Management</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item">Book</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            @canAccess('delete_product')
                <button id="bulkDelete" class="btn btn-danger mr-1 mb-1">Delete Selected</button>
            @endcanAccess

            @canAccess('create_product')
                <a class="btn btn-info mb-1" href="{{ url('admin/product/create') }}">Add Book</a>
            @endcanAccess

            @canAccess('view_trash_product')
                <a class="btn btn-warning ml-1 mb-1" href="{{ route('admin.product.trash') }}">View Trashed Books</a>
            @endcanAccess
        </div>
    </div>
</div>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Book List</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="row mb-4 align-items-end">
                        <div class="col-md-1">
                            <label>Status</label>
                            <select id="statusFilter" class="form-control">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- <div class="col-md-1">
                            <label>Featured</label>
                            <select id="featuredFilter" class="form-control">
                                <option value="">All</option>
                                <option value="1">Featured</option>
                                <option value="0">Not Featured</option>
                            </select>
                        </div> --}}

                        {{-- <div class="col-md-2">
                            <label>Category</label>
                            <select id="categoryFilter" class="form-control select2">
                                <option value="">All</option>
                            </select>
                        </div> --}}

                        {{-- <div class="col-md-2">
                            <label>Sub Category</label>
                            <select id="subCategoryFilter" class="form-control select2">
                                <option value="">All</option>
                            </select>
                        </div> --}}

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
                                    <th>Book Name</th>
                                    {{-- <th>Category</th> --}}
                                    {{-- <th>Sub Category</th> --}}
                                    {{-- <th>SKU</th> --}}
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    {{-- <th>Is Featured</th> --}}
                                    {{-- <th>Created At</th> --}}
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

    // Category Filter Select2
    $('#categoryFilter').select2({
        placeholder: 'Select Category',
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route('admin.product.categories.select2') }}",
            dataType: 'json',
            delay: 250,
            data: params => ({
                search: params.term || '',
                page: params.page || 1
            }),
            processResults: (data, params) => {
                params.page = params.page || 1;
                return {
                    results: data.results,
                    pagination: { more: data.pagination.more }
                };
            }
        }
    });

    // Subcategory Filter Select2
    function initSubcategoryFilter(categoryId) {
        $('#subCategoryFilter').select2({
            placeholder: 'Select Sub Category',
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ route('admin.product.subcategories.select2') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({
                    category_id: categoryId,
                    search: params.term || '',
                    page: params.page || 1
                }),
                processResults: (data, params) => {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: { more: data.pagination.more }
                    };
                }
            }
        });
    }

    // On Category Filter Change → update Subcategory Filter
    $('#categoryFilter').on('change', function () {
        let categoryId = $(this).val();
        $('#subCategoryFilter').val(null).trigger('change');

        if (categoryId) {
            initSubcategoryFilter(categoryId);
        } else {
            $('#subCategoryFilter').empty().append('<option value="">All</option>').trigger('change');
        }

        $('.yajra-datatable').DataTable().ajax.reload();
    });

    CRUDManager.init({
        tableSelector: '.yajra-datatable',
        entity: 'product',
        routes: {
            data: "{{ route('admin.product.data') }}",
            delete: "{{ route('admin.product.destroy', ':id') }}",
            toggleStatus: "{{ route('admin.product.toggleStatus', ':id') }}",
            bulkDelete: "{{ route('admin.product.bulkDelete') }}"
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
            {
                data: null,
                name: 'id',
                orderable: true,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'name', name: 'name'},
            // {data: 'category', name: 'category'},
            // {data: 'sub_category', name: 'sub_category'},
            // {data: 'sku', name: 'sku'},
            {data: 'base_price', name: 'base_price'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            // {data: 'is_featured', name: 'is_featured', orderable: false, searchable: false},
            // {
            //     data: 'created_at',
            //     name: 'created_at'
            // },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        extraFilters: function() {
            return {
                status: $('#statusFilter').val(),
                from_date: $('#fromDate').val(),
                to_date: $('#toDate').val(),
                featured: $('#featuredFilter').val(),
                category_id: $('#categoryFilter').val(),
                sub_category_id: $('#subCategoryFilter').val(),
            };
        }
    });

    $(document).on('mouseenter', '.toggleBannerStatus', function() {
        $(this).attr('title', $(this).is(':checked') ? 'Active' : 'Inactive');
    });

    // 🔽 Apply filter dynamically
    $('#statusFilter, #fromDate, #toDate, #subCategoryFilter, #featuredFilter').on('change', function () {
        $('.yajra-datatable').DataTable().ajax.reload();
    });
    $('#resetFilters').on('click', function () {
        $('#statusFilter').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        $('#featuredFilter').val('');
        $('#subCategoryFilter').val('');
        $('.yajra-datatable').DataTable().ajax.reload();
    });

    $(document).on('click', '.toggleProductIsFeatured', function () {
        let checkbox = $(this);
        let id = checkbox.data('id');
        let originalState = checkbox.prop('checked');
        let url = "{{ route('admin.product.toggleIsFeatured', ':id') }}".replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    $.toast({
                        heading: 'Success',
                        text: res.is_featured,
                        position: 'top-right',
                        icon: 'success',
                        loaderBg: '#5ba035',
                        hideAfter: 3000
                    });
                } else {
                    checkbox.prop('checked', !originalState);
                    $.toast({
                        heading: 'Error',
                        text: 'Unable to update status.',
                        position: 'top-right',
                        icon: 'error',
                        loaderBg: '#ff6849',
                        hideAfter: 3000
                    });
                }
            },
            error: function (xhr) {
                checkbox.prop('checked', !originalState);
                $.toast({
                    heading: 'Error',
                    text: 'Something went wrong.',
                    position: 'top-right',
                    icon: 'error',
                    loaderBg: '#ff6849',
                    hideAfter: 3000
                });
            }
        });
    });
});
</script>
@endpush
