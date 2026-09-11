<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Share branding settings with admin and frontend layouts.
        View::composer(['admin.*', 'frontend.layouts.*'], function ($view) {
            $view->with('appSetting', AppSetting::getSettings());
        });

        // Share top categories with frontend layouts.
        View::composer(['frontend.layouts.*'], function ($view) {
            $footerCategories = Category::where('status', 'active')
                ->withCount(['books' => fn ($q) => $q->where('status', 'active')])
                ->orderBy('sort_order')
                ->orderBy('title')
                ->take(6)
                ->get();

            $view->with('footerCategories', $footerCategories);
        });
    }
}
