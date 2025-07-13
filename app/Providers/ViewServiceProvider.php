<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Color;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $siteTitle = Setting::getValue('site_title', 'WebBuilder');
            $colors = Color::pluck('hex_code', 'variable_name');
            $view->with(compact('siteTitle', 'colors'));
        });
    }
}
