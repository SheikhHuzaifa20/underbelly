<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // If primary image changed, delete old primary image file(s)
        if ($product->isDirty('images')) {
            $oldPrimaryImage = $product->getOriginal('primary_image_path') ?? null;

            if ($oldPrimaryImage && File::exists(public_path($oldPrimaryImage))) {
                File::delete(public_path($oldPrimaryImage));
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // Soft delete: just leave images as they are
        // If product is force deleted, remove all associated images
        if ($product->isForceDeleting()) {
            foreach ($product->images as $image) {
                if ($image->image_path && File::exists(public_path($image->image_path))) {
                    File::delete(public_path($image->image_path));
                }
            }
            // Delete image records from database
            $product->images()->delete();
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        // Nothing special needed here unless you want to restore images as well
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        // Already handled in deleted() method
    }
}
