<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\customer\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\vendor\ProductController;
use App\Http\Controllers\vendor\VendorController;
use Illuminate\Support\Facades\Route;


Route::get('registerForm', [RegisterController::class, 'index'])->name('registerForm');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('loginForm', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('login', [loginController::class, 'login'])->name('login');

//customer Routes

//vendor routes
Route::prefix('vendor')->group(function () {
    Route::get('home', [VendorController::class, 'index'])->name('vendor.dashboard');
    Route::get('products', [ProductController::class, 'index'])->name('vendor.product');
    Route::post('products/store', [ProductController::class, 'store'])->name('vendor.product.store');
    Route::get('products/show', [ProductController::class, 'show'])->name('vendor.product.show');
    Route::put('products/{id}/edit', [ProductController::class, 'update'])->name('vendor.product.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('vendor.product.destroy');
    Route::get('subcategories/{categoryId}', [ProductController::class, 'getSubcategories'])->name('vendor.subcategories');
});


Route::prefix('customer')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('product-details/{id}', [HomeController::class, 'productDetails'])->name('product.details');
    // Route::get('add-to-cart/{id}', [HomeController::class, 'addToCart'])->name('add.to.cart');
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
    
    //testing routes
    Route::get('/profile', [CustomerController::class, 'ShowProfile'])->name('customer.profile');
    Route::get('/wishlist', [CustomerController::class, 'ShowWishlist'])->name('customer.wishlist');
    Route::get('/checkout', [HomeController::class, 'ShowCheckout'])->name('customer.checkout');
});
