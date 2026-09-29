<?php

namespace App\Observers;

use App\Models\Blog;
use Illuminate\Support\Facades\File;

class BlogObserver
{
    public function created(Blog $blog): void
    {
        //
    }

    public function updated(Blog $blog): void
    {
        if ($blog->isDirty('image')) {
            $oldImage = $blog->getOriginal('image');
            if ($oldImage && File::exists(public_path($oldImage))) {
                File::delete(public_path($oldImage));
            }
        }
    }

    public function deleted(Blog $blog): void
    {
        if ($blog->isForceDeleting()) {
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
        }
    }

    public function restored(Blog $blog): void
    {
        //
    }

    public function forceDeleted(Blog $blog): void
    {
        //
    }
}