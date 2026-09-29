@extends('layouts.app')
@push('before-css')
    <link rel="stylesheet" href="{{ asset('plugins/vendors/dropify/dist/css/dropify.min.css') }}">
    <style>
        .tag {
            display: inline-flex;
            align-items: center;
            background: #0d6efd;
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            margin: 2px;
            font-size: 14px;
        }

        .tag .remove-tag {
            margin-left: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .tag-error {
            background: #dc3545 !important;
            color: white;
        }

        #tags-input {
            border: none;
            outline: none;
            padding: 5px;
            min-width: 120px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 28px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #0d6efd;
        }

        input:checked+.slider:before {
            transform: translateX(27px);
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">Edit Book</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/product') }}">Book Management</a></li>
                        <li class="breadcrumb-item active">Edit Book</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="basic-form-layouts">
            <form class="form" enctype="multipart/form-data" method="post"
                action="{{ route('admin.product.update', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="row match-height">
                    <div class="col-md-7">
                        <!-- Product Info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Book Info</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Book Name</label>
                                                    <input class="form-control" required name="name" type="text"
                                                        id="name" value="{{ old('name', $product->name) }}">
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sku">SKU</label>
                                                <input class="form-control" required name="sku" type="text"
                                                    id="sku" value="{{ old('sku', $product->sku) }}">
                                            </div>
                                        </div> --}}
                                            {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slug">Slug</label>
                                                <input class="form-control" required name="slug" type="text"
                                                    id="slug" value="{{ old('slug', $product->slug) }}">
                                            </div>
                                        </div> --}}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="summary-ckeditor">Description</label>
                                                    <textarea name="description" id="summary-ckeditor" cols="30" rows="10" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Book Image</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Main Image</label>
                                        <input class="form-control dropify" name="image" type="file" id="image"
                                            {{ $primary_image ? 'data-default-file="' . asset($primary_image) . '"' : '' }}>
                                    </div>
                                    <div class="form-group">
                                        <label>Gallery Images</label>
                                        <input class="form-control dropify" name="images[]" type="file" id="images"
                                            multiple>

                                        @if ($gallery_images)
                                            <div class="mt-2" id="gallery-images-container">
                                                @foreach ($gallery_images as $img)
                                                    <div class="gallery-image-wrapper" data-id="{{ $img->id }}"
                                                        style="display:inline-block; position:relative; margin:5px;">
                                                        <img src="{{ asset($img->image_path) }}" width="60"
                                                            class="rounded" />
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger remove-gallery-btn"
                                                            style="position:absolute; top:0; right:0; padding:2px 5px;">
                                                            &times;
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Variations -->
                        <div class="card">
                            {{-- <div class="card-header">
                            <h4 class="card-title">Book Variations</h4>
                        </div> --}}
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    {{-- <div class="repeater-default col-md-12">
                                    <div data-repeater-list="product_attributes">
                                        @foreach ($product->attributes as $attribute)
                                            <div data-repeater-item class="row align-items-end" data-value="{{ $attribute->value_id }}">
                                                <div class="form-group col-md-3">
                                                    <label>Attribute</label>
                                                    <select class="form-control attribute_id select2" name="attribute_id">
                                                        <option value="">Select Attribute</option>
                                                        @foreach ($attributes as $att)
                                                            <option value="{{ $att->id }}" {{ $attribute->attribute_id == $att->id ? 'selected' : '' }}>{{ $att->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Value</label>
                                                    <select class="form-control value select2" name="value" data-selected="{{ $attribute->value }}">
                                                        <option value="">Select Value</option>
                                                        <!-- options will be loaded via JS AJAX -->
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Price</label>
                                                    <input type="number" class="form-control" name="price" value="{{ $attribute->price }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Qty</label>
                                                    <input type="number" class="form-control" name="qty" value="{{ $attribute->qty }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button type="button" class="btn btn-danger" data-repeater-delete>Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" data-repeater-create class="btn btn-primary mt-1">
                                        <i class="ft-plus"></i> Add
                                    </button>
                                </div> --}}
                                    <div class="form-actions text-right pb-0">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> Update Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-5">
                        <!-- Messages & Alerts -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Information</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="card-text">
                                        @if ($errors->any())
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li class="alert alert-danger">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if (Session::has('message'))
                                            <ul>
                                                <li class="alert alert-success">{{ Session::get('message') }}</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Pricing</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="base_price">Price</label>
                                                <input class="form-control" required name="base_price" type="number"
                                                    step="any" id="base_price"
                                                    value="{{ old('base_price', $product->base_price) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="link">Book link</label>
                                                <input class="form-control" name="link" type="text" id="link"
                                                    value="{{ old('link', $product->link) }}">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount_price">Discount Price</label>
                                            <input class="form-control" name="discount_price" type="number" step="any"
                                                id="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
                                        </div>
                                    </div> --}}
                                        {{-- <div class="col-md-6 ml-1">
                                        <div class="form-group">
                                            <label>Charge tax on this Book</label><br>
                                            <label class="switch">
                                                <input type="checkbox" name="is_charge_tax" id="is_charge_tax" {{ $product->is_charge_tax ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div> --}}
                                        {{-- <div class="col-md-6 ml-1">
                                        <div class="form-group">
                                            <label>Stock</label><br>
                                            <label class="switch">
                                                <input type="checkbox" name="stock" id="stock" {{ $product->stock ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Organize -->
                        <div class="card">
                            {{-- <div class="card-header">
                            <h4 class="card-title">Organize</h4>
                        </div> --}}
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    {{-- Category and subcategory removed: set to null automatically --}}

</div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('plugins/vendors/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(function() {
            // Initialize Dropify
            $('.dropify').dropify();
        });

        $(document).ready(function() {

            function initSelect2(container = document) {
                $(container).find('.select2').select2({
                    width: '100%'
                });
            }

            // Repeater init
            $('.repeater-default').repeater({
                show: function() {
                    $(this).slideDown();
                    initSelect2(this); // re-init select2 on new row
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

            initSelect2(); // first load

            // Attribute change event (works for repeater)
            $(document).on('change', '.attribute_id', function() {
                let attributeId = $(this).val();
                let row = $(this).closest('[data-repeater-item]');
                let valueSelect = row.find('.value');

                valueSelect.html('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('admin.product.get-attribute-values') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        attribute_id: attributeId
                    },
                    success: function(res) {
                        valueSelect.empty().append('<option value="">Select Value</option>');
                        if (res.status) {
                            $.each(res.data, function(i, val) {
                                valueSelect.append(
                                    `<option value="${val.id}">${val.value}</option>`
                                );
                            });

                            // For edit: select the current value if data attribute exists
                            let selectedValue = valueSelect.data('selected'); // set in Blade
                            if (selectedValue) {
                                valueSelect.val(selectedValue).trigger('change');
                            }
                        }
                    }
                });
            });

            // On page load: trigger AJAX for each existing row
            $('[data-repeater-item]').each(function() {
                let row = $(this);
                let attributeSelect = row.find('.attribute_id');
                let valueSelect = row.find('.value');

                let attributeId = attributeSelect.val();
                let selectedValue = row.data('value'); // set in Blade
                valueSelect.attr('data-selected', selectedValue);

                if (attributeId) {
                    attributeSelect.trigger('change');
                }
            });

            let categorySelected = $('#category').data('selected');
            let subcategorySelected = $('#subcategory').data('selected');

            // 🔹 CATEGORY SELECT2 (Infinite Scroll)
            $('#category').select2({
                placeholder: 'Select Category',
                width: '100%',
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
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    }
                }
            });

            // 🔹 SUBCATEGORY SELECT2 (Depends on Category)
            function initSubcategory(categoryId) {
                $('#subcategory').select2({
                    placeholder: 'Select Sub Category',
                    width: '100%',
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
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        }
                    }
                });
            }

            // 🔹 On Category Change
            $('#category').on('change', function() {
                let categoryId = $(this).val();
                $('#subcategory').val(null).trigger('change');

                if (categoryId) {
                    $('#subcat-container').show();
                    initSubcategory(categoryId);
                } else {
                    $('#subcat-container').hide();
                }
            });

            // 🔹 PRESELECT CATEGORY (Edit / old)
            if (categorySelected) {
                $.get("{{ route('admin.product.categories.select2') }}", {
                    id: categorySelected
                }, function(data) {
                    let option = new Option(data.text, data.id, true, true);
                    $('#category').append(option).trigger('change');
                });
            }

            // 🔹 PRESELECT SUBCATEGORY
            if (subcategorySelected && categorySelected) {
                $('#subcat-container').show();
                initSubcategory(categorySelected);

                $.get("{{ route('admin.product.subcategories.select2') }}", {
                    id: subcategorySelected
                }, function(data) {
                    let option = new Option(data.text, data.id, true, true);
                    $('#subcategory').append(option).trigger('change');
                });
            }

            // Tags input handling
            let tags = [];
            const tagInput = document.getElementById('tags-input');
            const tagBox = document.getElementById('tags-box');
            const hiddenField = document.getElementById('tags-hidden');

            // Load old tags from old() or existing product tags
            let existingTags = hiddenField.value ? hiddenField.value.split(',') : [];
            existingTags.forEach(t => addTag(t));

            // Add new tag on Enter
            tagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    let value = tagInput.value.trim();
                    if (value === "") return;

                    if (tags.includes(value.toLowerCase())) {
                        showDuplicateError(value);
                        tagInput.value = "";
                        return;
                    }

                    addTag(value);
                    tagInput.value = "";
                }
            });

            function addTag(text) {
                text = text.trim();
                if (!text) return;

                tags.push(text.toLowerCase());

                const tag = document.createElement('span');
                tag.classList.add('tag');
                tag.innerHTML = `${text}<span class="remove-tag">&times;</span>`;

                tag.querySelector('.remove-tag').addEventListener('click', function() {
                    tag.remove();
                    tags = tags.filter(t => t !== text.toLowerCase());
                    hiddenField.value = tags.join(',');
                });

                tagBox.insertBefore(tag, tagInput);
                hiddenField.value = tags.join(',');
            }

            function showDuplicateError(text) {
                const errorTag = document.createElement('span');
                errorTag.classList.add('tag', 'tag-error');
                errorTag.innerHTML = text;
                tagBox.insertBefore(errorTag, tagInput);
                setTimeout(() => errorTag.remove(), 1200);
            }


            $(document).on('click', '.remove-gallery-btn', function() {
                if (!confirm('Are you sure you want to delete this image?')) return;

                let wrapper = $(this).closest('.gallery-image-wrapper');
                let imageId = wrapper.data('id');

                $.ajax({
                    url: "{{ route('admin.product.gallery.destroy') }}", // create this route
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: imageId
                    },
                    success: function(res) {
                        if (res.success) {
                            wrapper.remove();
                            $.toast({
                                heading: 'Success',
                                text: 'Image deleted successfully.',
                                position: 'top-right',
                                icon: 'success',
                                loaderBg: '#5ba035',
                                hideAfter: 3000
                            });
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: 'Unable to delete image.',
                                position: 'top-right',
                                icon: 'error',
                                loaderBg: '#ff6849',
                                hideAfter: 3000
                            });
                        }
                    },
                    error: function() {
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
