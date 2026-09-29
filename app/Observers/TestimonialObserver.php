<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\File;

class TestimonialObserver
{
    public function created(Testimonial $testimonial): void
    {
        //
    }

    public function updated(Testimonial $testimonial): void
    {
        if ($testimonial->isDirty('image')) {
            $oldImage = $testimonial->getOriginal('image');
            if ($oldImage && File::exists(public_path($oldImage))) {
                File::delete(public_path($oldImage));
            }
        }
    }

    public function deleted(Testimonial $testimonial): void
    {
        if ($testimonial->isForceDeleting()) {
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }
        }
    }

    public function restored(Testimonial $testimonial): void
    {
        //
    }

    public function forceDeleted(Testimonial $testimonial): void
    {
        //
    }
}