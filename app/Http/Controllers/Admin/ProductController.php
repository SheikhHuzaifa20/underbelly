<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Traits\FileUploadTrait;

class ProductController extends Controller
{
    use FileUploadTrait;
    /**
     * PRODUCT LIST PAGE
     */
    public function index()
    {
        return view('admin.product.index');
    }

    /**
     * DATATABLE LISTING
     */
    public function getData(Request $request)
    {
        $products = Product::with('category', 'subCategory', 'primaryImage')
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $products->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $products->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        // if( $request->filled('featured') ) {
        //     $products->where('is_featured', $request->featured);
        // }

        // if ($request->filled('category_id')) {
        //     $products->where('category_id', $request->category_id);
        // }

        // if ($request->filled('sub_category_id')) {
        //     $products->where('sub_category_id', $request->sub_category_id);
        // }

        return datatables()->of($products)
            // ->addColumn('category', fn ($row) => $row->category->name ?? '-')
            // ->addColumn('sub_category', fn ($row) => $row->subCategory->name ?? '-')
            ->addColumn('image', function ($row) {
                $image = $row->primaryImage && $row->primaryImage->image_path
                    ? asset($row->primaryImage->image_path)
                    : asset('images/noimage.png'); // fallback image

                return '<img src="' . $image . '" width="120">';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" class="toggleProductStatus" data-id="' . $row->id . '" ' . $checked . '>
                        <span class="slider round" title="Click to toggle status"></span>
                    </label>
                ';
            })
            // ->addColumn('is_featured', function ($row) {
            //     $checked = $row->is_featured ? 'checked' : '';
            //     return '
            //         <label class="switch">
            //             <input type="checkbox" class="toggleProductIsFeatured" data-id="' . $row->id . '" ' . $checked . '>
            //             <span class="slider round" title="Click to toggle featured status"></span>
            //         </label>
            //     ';
            // })
            // ->addColumn('created_at', function ($row) {
            //     return $row->created_at ? $row->created_at->format('d M, Y h:i A') : '-';
            // })
            ->addColumn('action', function ($row) {
                $actions = '';
                if (auth()->user()->hasPermission('edit_product')) {
                    $actions .= '<a href="' . route('admin.product.edit', $row->id) . '"
                                    class="btn btn-sm btn-info" title="Edit User">
                                    <i class="la la-pencil"></i>
                                </a> ';
                }
                if (auth()->user()->hasPermission('delete_product')) {
                    $actions .= '<button class="btn btn-sm btn-danger deleteProduct"
                                    data-id="' . $row->id . '" title="Delete User">
                                    <i class="la la-trash"></i>
                                </button>';
                }
                return $actions ?: '<span class="text-muted">No actions</span>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('admin.product.create', [
            'attributes' => Attribute::with('values')->where('status', 1)->get(),
        ]);
    }

    /**
     * STORE PRODUCT
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['link'] = $request->input('link');

            // Generate unique slug
            $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($data['name']);
            $slug = $baseSlug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $data['slug'] = $slug;

            $data['created_by']     = auth()->id();
            $data['category_id']    = null;
            $data['stock']          = null;

            //is_charge_tax default value
            $data['is_charge_tax'] = $request->is_charge_tax == "on" ? 1 : 0;

            // -------------------------
            // CREATE PRODUCT
            $product = Product::create($data);

            // -------------------------
            // SAVE PRIMARY IMAGE
            // -------------------------
            if ($request->hasFile('image')) {
                $primaryImagePath = $this->uploadFile(
                    $request->file('image'),
                    'uploads/products/',
                    'product'
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $primaryImagePath,
                    'is_primary' => 1,
                ]);
            }

            // -------------------------
            // SAVE GALLERY IMAGES
            // -------------------------
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $galleryFile) {
                    $galleryPath = $this->uploadFile(
                        $galleryFile,
                        'uploads/products/',
                        'gallery'
                    );

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $galleryPath,
                        'is_primary' => 0,
                    ]);
                }
            }

            // -------------------------
            // FALLBACK: MAKE FIRST IMAGE PRIMARY
            // -------------------------
            if (!$product->images()->where('is_primary', 1)->exists()) {
                $firstImage = $product->images()->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => 1]);
                }
            }

            // -------------------------
            // SAVE SIMPLE PRODUCT ATTRIBUTES
            // -------------------------
            if ($request->has('product_attributes')) {
                foreach ($request->product_attributes as $attr) {
                    ProductAttribute::create([
                        'product_id'   => $product->id,
                        'attribute_id' => $attr['attribute_id'],
                        'value'        => $attr['value'],
                        'price'        => $attr['price'] ?? 0,
                        'qty'          => $attr['qty'] ?? 0,
                    ]);
                }
            }

            // -------------------------
            // SAVE PRODUCT VARIANTS
            // -------------------------
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    $product->variants()->create([
                        'attributes' => json_encode($variant['attributes']),
                        'sku'        => $variant['sku'] ?? null,
                        'price'      => $variant['price'] ?? 0,
                        'stock'      => $variant['stock'] ?? 0,
                        'status'     => 1,
                    ]);
                }
            }

            $newData = [
                'product' => $product->toArray(),

                'images' => $product->images()
                    ->get()
                    ->toArray(),

                'attributes' => $product->attributes()
                    ->get()
                    ->toArray(),

                'attribute_values' => $product->attributes()
                    ->with('attribute.values')
                    ->get()
                    ->pluck('attribute.values')
                    ->flatten(1)
                    ->toArray(),
            ];

            // -------------------------
            // LOG ACTIVITY
            // -------------------------
            log_activity(
                'create',
                Product::class,
                $product->id,
                'Created new product: ' . $product->name,
                ['newData' => $newData]
            );

            DB::commit();

            return redirect()
                ->route('admin.product.index')
                ->with('message', 'Product added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * EDIT PAGE
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', [
            'product'     => $product->load('primaryImage', 'attributes'),
            'primary_image'      => $product->primaryImage->image_path ?? '',
            'gallery_images'      => $product->galleryImages,
            'categories'  => Category::where('status', 1)->get(),
            'subCats'     => SubCategory::where('category_id', $product->category_id)->get(),
            'attributes'  => Attribute::with('values')->where('status', 1)->get(),
            'variants'    => $product->variants ?? [],
        ]);
    }

    /**
     * UPDATE PRODUCT
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $oldData = $product->toArray();

            $data = $request->validated();
            $data['link'] = $request->input('link');

            // -------------------------
            // DEFAULT VALUES
            // -------------------------
            $data['category_id']     = null;
            $data['sub_category_id'] = null;
            $data['stock']           = null;
            $data['is_charge_tax']   = $request->is_charge_tax == "on" ? 1 : 0;

            // Generate unique slug (excluding current product)
            $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($data['name']);
            $slug = $baseSlug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $data['slug']            = $slug;

            $data['tags']            = $request->tags ?? null;

            $product->update($data);

            // -------------------------
            // UPDATE PRIMARY IMAGE
            // -------------------------
            if ($request->hasFile('image')) {
                $oldPrimary = $product->images()->where('is_primary', 1)->first();
                if ($oldPrimary) {
                    $this->deleteFile($oldPrimary->image_path);
                    $oldPrimary->delete();
                }

                $primaryImagePath = $this->uploadFile(
                    $request->file('image'),
                    'uploads/products/',
                    'product'
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $primaryImagePath,
                    'is_primary' => 1,
                ]);
            }

            // -------------------------
            // UPDATE GALLERY IMAGES
            // -------------------------
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $galleryFile) {
                    $galleryPath = $this->uploadFile(
                        $galleryFile,
                        'uploads/products/',
                        'gallery'
                    );

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $galleryPath,
                        'is_primary' => 0,
                    ]);
                }
            }

            // -------------------------
            // UPDATE PRODUCT ATTRIBUTES
            // -------------------------
            if ($request->has('product_attributes')) {
                $product->attributes()->delete();

                foreach ($request->product_attributes as $attr) {
                    if (empty($attr['attribute_id'])) continue;

                    ProductAttribute::create([
                        'product_id'   => $product->id,
                        'attribute_id' => $attr['attribute_id'],
                        'value'        => $attr['value'] ?? null,
                        'price'        => $attr['price'] ?? 0,
                        'qty'          => $attr['qty'] ?? 0,
                    ]);
                }
            }

            // -------------------------
            // UPDATE PRODUCT VARIANTS
            // -------------------------
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    if (!empty($variantData['id'])) {
                        $variant = $product->variants()->find($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'attributes' => json_encode($variantData['attributes'] ?? []),
                                'sku'        => $variantData['sku'] ?? null,
                                'price'      => $variantData['price'] ?? 0,
                                'stock'      => $variantData['stock'] ?? 0,
                            ]);
                        }
                    } else {
                        $product->variants()->create([
                            'attributes' => json_encode($variantData['attributes'] ?? []),
                            'sku'        => $variantData['sku'] ?? null,
                            'price'      => $variantData['price'] ?? 0,
                            'stock'      => $variantData['stock'] ?? 0,
                            'status'     => 1,
                        ]);
                    }
                }
            }

            // -------------------------
            // LOG ACTIVITY
            // -------------------------
            log_activity(
                'update',
                Product::class,
                $product->id,
                'Updated product: ' . $product->name,
                [
                    'before' => $oldData,
                    'after'  => $product->fresh()->toArray(),
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.product.edit', $product)
                ->with('message', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * DELETE PRODUCT (Soft Delete)
     */
    public function destroy(Product $product)
    {
        $product->delete();

        log_activity('delete', Product::class, $product->id, "Deleted product {$product->name}");

        return response()->json(['success' => 'Product deleted successfully.']);
    }

    /**
     * TOGGLE STATUS
     */
    public function toggleStatus(Product $product)
    {
        $old = $product->status;
        $product->status = !$old;
        $product->save();

        log_activity(
            'status_toggle',
            Product::class,
            $product->id,
            'Toggled product status for ' . $product->name . ' from ' . ($old ? 'Active' : 'Inactive') . ' to ' . ($product->status ? 'Active' : 'Inactive')
        );

        return response()->json([
            'success' => true,
            'status' => $product->status ? 'Active' : 'Inactive',
        ]);
    }

    /**
     * TOGGLE IS FEATURED
     */
    public function toggleIsFeatured(Product $product)
    {
        $old = $product->is_featured;
        $product->is_featured = !$old;
        $product->save();

        log_activity(
            'is_featured_toggle',
            Product::class,
            $product->id,
            'Toggled product ' . $product->name . ' from ' . ($old ? 'Featured' : 'Not Featured') . ' to ' . ($product->is_featured ? 'Featured' : 'Not Featured'),
        );

        return response()->json([
            'success' => true,
            'is_featured' => $product->is_featured ? 'Featured' : 'Not Featured',
        ]);
    }

    /**
     * TRASH PAGE
     */
    public function trash()
    {
        return view('admin.product.trash');
    }

    /**
     * TRASH AJAX DATA
     */
    public function getTrashedData()
    {
        $products = Product::with('category', 'subCategory', 'primaryImage')->onlyTrashed()->get();

        return datatables()->of($products)
            ->addColumn('category', fn($row) => $row->category->name ?? '-')
            ->addColumn('sub_category', fn($row) => $row->subCategory->name ?? '-')
            ->addColumn('image', function ($row) {
                $image = $row->primaryImage && $row->primaryImage->image_path
                    ? asset($row->primaryImage->image_path)
                    : asset('images/noimage.png'); // fallback image

                return '<img src="' . $image . '" width="120">';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-success restoreProduct" data-id="' . $row->id . '">Restore</button>
                    <button class="btn btn-sm btn-danger forceDeleteProduct" data-id="' . $row->id . '">Delete Permanently</button>
                ';
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        log_activity('restore', Product::class, $product->id, "Restore product {$product->name}");

        return response()->json(['success' => 'Product restored successfully.']);
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // $product->images()->delete();
        $product->forceDelete();

        log_activity('force_delete', Product::class, $id, "Permanently deleted product {$product->name}");

        return response()->json(['success' => 'Product permanently deleted.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No items selected.'], 400);
        }

        Product::whereIn('id', $ids)->delete();

        log_activity('bulk_delete', Product::class, null, 'Bulk deleted products: ' . implode(',', $ids), ['ids' => $ids]);

        return response()->json(['success' => 'Selected products deleted successfully.']);
    }

    // Bulk restore from trash
    public function bulkRestore(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No items selected.'], 400);
        }

        Product::withTrashed()->whereIn('id', $ids)->restore();

        log_activity('bulk_restore', Product::class, null, 'Bulk restore products: ' . implode(',', $ids), ['ids' => $ids]);

        return response()->json(['success' => 'Selected products restored successfully.']);
    }

    // Bulk permanent delete (from trash)
    public function bulkForceDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No items selected.'], 400);
        }

        $products = Product::withTrashed()->whereIn('id', $ids)->get();

        log_activity('bulk_force_delete', Product::class, null, 'Bulk permanently deleted products: ' . implode(',', $ids), ['ids' => $ids]);

        foreach ($products as $product) {
            $this->deleteFile($product->primary_image_path);
            $product->forceDelete();
        }

        return response()->json(['success' => 'Selected products permanently deleted.']);
    }

    public function getSubcategories($id)
    {
        $subcategories = SubCategory::where('category_id', $id)->get();

        return response()->json($subcategories);
    }

    public function getValues(Request $request)
    {
        $values = AttributeValue::where('attribute_id', $request->attribute_id)->get();

        return response()->json([
            'status' => true,
            'data' => $values
        ]);
    }

    public function destroyGalleryImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_images,id'
        ]);

        $image = ProductImage::findOrFail($request->id);

        // Delete file from storage
        if (file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    public function catSelect2(Request $request)
    {
        if ($request->filled('id')) {
            $cat = Category::find($request->id);
            return response()->json(['id' => $cat->id, 'text' => $cat->name]);
        }

        $query = Category::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $data = $query->paginate(10);

        return response()->json([
            'results' => $data->map(fn($c) => ['id' => $c->id, 'text' => $c->name]),
            'pagination' => ['more' => $data->hasMorePages()]
        ]);
    }

    public function subCatselect2(Request $request)
    {
        if ($request->filled('id')) {
            $sub = SubCategory::find($request->id);
            return response()->json(['id' => $sub->id, 'text' => $sub->name]);
        }

        $query = SubCategory::where('category_id', $request->category_id);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $data = $query->paginate(10);

        return response()->json([
            'results' => $data->map(fn($s) => ['id' => $s->id, 'text' => $s->name]),
            'pagination' => ['more' => $data->hasMorePages()]
        ]);
    }
}
