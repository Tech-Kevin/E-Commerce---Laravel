<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\Wishlist;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
        try {
            if (Schema::hasTable('site_settings')) {
                View::share('siteSetting', SiteSetting::getSettings());
            } else {
                View::share('siteSetting', null);
            }
        } catch (Throwable $e) {
            View::share('siteSetting', null);
        }

        View::composer(['customer.partials.footer', 'layouts.store'], function ($view) {
            $view->with('navCategories', Category::where('status', true)->get());

            $cartCount = count(session()->get('cart', []));
            $wishlistCount = Auth::check()
                ? Wishlist::where('user_id', Auth::id())->count()
                : 0;

            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
