<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
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
        View::composer('customer.partials.footer', function ($view) {
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
