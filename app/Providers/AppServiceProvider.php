<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\LandingPage;
use App\Models\ProductType;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.bootstrap-5');

        View::composer('layouts.app', function ($view) {
            $view->with([
                'navProductTypes'   => ProductType::active()->orderBy('sort_order')->get(),
                'navCategories'     => Category::active()->parents()->with(['children' => fn($q) => $q->active()->orderBy('sort_order')])->orderBy('sort_order')->get(),
                'activeLandingPage' => LandingPage::where('is_active', true)->first(),
            ]);
        });
    }
}
