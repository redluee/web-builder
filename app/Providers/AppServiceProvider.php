<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PageElement;
use App\Observers\PageElementObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PageElement::observe(PageElementObserver::class);
    }
}
