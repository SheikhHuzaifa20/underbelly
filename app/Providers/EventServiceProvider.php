<?php

namespace App\Providers;

use App\Models\Blog;
use App\Observers\BlogObserver;

use App\Models\Testimonial;
use App\Observers\TestimonialObserver;

use App\Models\Testimonials;
use App\Observers\TestimonialsObserver;


use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Banner;
use App\Observers\BannerObserver;
use App\Models\Product;
use App\Observers\ProductObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Testimonial::observe(TestimonialObserver::class);
        Banner::observe(BannerObserver::class);
        Product::observe(ProductObserver::class);
    }
}
