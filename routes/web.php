<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\vendor\ProductController;
use App\Http\Controllers\vendor\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('registerForm', [RegisterController::class, 'index'])->name('registerForm');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('loginForm', [loginController::class, 'loginForm'])->name('loginForm');
Route::post('login', [loginController::class, 'login'])->name('login');
Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('loginForm');
})->name('logout');

// Customer routes
Route::prefix('customer')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('product-details/{id}', [HomeController::class, 'productDetails'])->name('product.details');

    // Cart
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [HomeController::class, 'ShowCheckout'])->name('customer.checkout');
    Route::post('/checkout/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders');

    // Profile
    Route::get('/profile', [CustomerController::class, 'ShowProfile'])->name('customer.profile');
    Route::put('/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Wishlist
    Route::get('/wishlist', [CustomerController::class, 'ShowWishlist'])->name('customer.wishlist');
    Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Vendor routes
Route::prefix('vendor')->group(function () {
    Route::get('home', [VendorController::class, 'index'])->name('vendor.dashboard');
    Route::get('products', [ProductController::class, 'index'])->name('vendor.product');
    Route::post('products/store', [ProductController::class, 'store'])->name('vendor.product.store');
    Route::get('products/show', [ProductController::class, 'show'])->name('vendor.product.show');
    Route::put('products/{id}/edit', [ProductController::class, 'update'])->name('vendor.product.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('vendor.product.destroy');
    Route::get('subcategories/{categoryId}', [ProductController::class, 'getSubcategories'])->name('vendor.subcategories');

    Route::get('orders', [VendorController::class, 'ShowOrders'])->name('vendor.orders');
    Route::patch('orders/{order}/status', [VendorController::class, 'updateOrderStatus'])->name('vendor.order.status');
    Route::get('customers', [VendorController::class, 'ShowCustomers'])->name('vendor.customers');
    Route::get('analytics', [VendorController::class, 'ShowAnalytics'])->name('vendor.analytics');
    Route::get('earnings', [VendorController::class, 'ShowEarnings'])->name('vendor.earnings');
    Route::get('settings', [VendorController::class, 'ShowSettings'])->name('vendor.settings');
    Route::put('settings/update', [VendorController::class, 'updateSettings'])->name('vendor.settings.update');
});
