<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
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
        // Authorization Gate for admin permission slug checks
        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'hasPermission') && $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });
        // Share branding settings with all views.
        View::composer('*', function ($view) {
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
