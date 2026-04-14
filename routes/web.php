<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\delivery\DeliveryController;
use App\Http\Controllers\vendor\CategoryController;
use App\Http\Controllers\vendor\ProductController;
use App\Http\Controllers\vendor\SaleController;
use App\Http\Controllers\vendor\SuperAdminController;
use App\Http\Controllers\vendor\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth routes — guest only (redirect logged-in users to their dashboard)
Route::middleware('guest')->group(function () {
    Route::get('registerForm', [RegisterController::class, 'index'])->name('registerForm');
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::get('loginForm', [loginController::class, 'loginForm'])->name('loginForm');
    Route::post('login', [loginController::class, 'login'])->name('login');
});

// Language switch — works for all users (guest + logged-in)
Route::post('language/switch', function (Request $request) {
    $request->validate(['locale' => 'required|in:en,hi']);
    session(['locale' => $request->locale]);
    if (Auth::check()) {
        Auth::user()->update(['locale' => $request->locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('loginForm');
})->name('logout');

// Customer routes — public (no login required)
Route::prefix('customer')->middleware('set-locale')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('product-details/{id}', [HomeController::class, 'productDetails'])->name('product.details');
    Route::get('category/{slug}', [HomeController::class, 'categoryProducts'])->name('category.products');

    // Cart — session-based, no login required
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
});

// Customer routes — login required
Route::prefix('customer')->middleware(['customer', 'set-locale'])->group(function () {
    // Checkout & Orders
    Route::get('/checkout', [HomeController::class, 'ShowCheckout'])->name('customer.checkout');
    Route::post('/checkout/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders');
    Route::post('/razorpay/verify', [OrderController::class, 'razorpayVerify'])->name('razorpay.verify');
    Route::get('/razorpay/cancel', [OrderController::class, 'razorpayCancel'])->name('razorpay.cancel');

    // Profile
    Route::get('/profile', [CustomerController::class, 'ShowProfile'])->name('customer.profile');
    Route::put('/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Wishlist
    Route::get('/wishlist', [CustomerController::class, 'ShowWishlist'])->name('customer.wishlist');
    Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Delivery routes — login required
Route::prefix('delivery')->middleware(['delivery', 'set-locale'])->group(function () {
    Route::get('home', [DeliveryController::class, 'index'])->name('delivery.dashboard');
    Route::get('orders', [DeliveryController::class, 'assignedOrders'])->name('delivery.orders');
    Route::get('completed', [DeliveryController::class, 'completedOrders'])->name('delivery.completed');
    Route::patch('orders/{order}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.update.status');
    Route::get('orders/{order}/verify', [DeliveryController::class, 'showVerifyPage'])->name('delivery.verify');
    Route::post('orders/{order}/send-otp', [DeliveryController::class, 'sendOtp'])->name('delivery.send.otp');
    Route::post('orders/{order}/verify-otp', [DeliveryController::class, 'verifyOtp'])->name('delivery.verify.otp');
    Route::post('orders/{order}/confirm', [DeliveryController::class, 'confirmDelivery'])->name('delivery.confirm');
    Route::get('settings', [DeliveryController::class, 'settings'])->name('delivery.settings');
    Route::put('settings/update', [DeliveryController::class, 'updateSettings'])->name('delivery.settings.update');
    Route::post('language', [DeliveryController::class, 'switchLanguage'])->name('delivery.language');
});

// Vendor routes — login required
Route::prefix('vendor')->middleware('vendor')->group(function () {
    Route::get('home', [VendorController::class, 'index'])->name('vendor.dashboard');
    Route::get('products', [ProductController::class, 'index'])->name('vendor.product');
    Route::post('products/store', [ProductController::class, 'store'])->name('vendor.product.store');
    Route::get('products/show', [ProductController::class, 'show'])->name('vendor.product.show');
    Route::put('products/{id}/edit', [ProductController::class, 'update'])->name('vendor.product.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('vendor.product.destroy');
    Route::get('subcategories/{categoryId}', [ProductController::class, 'getSubcategories'])->name('vendor.subcategories');

    Route::get('sales', [SaleController::class, 'index'])->name('vendor.sales');
    Route::post('sales/store', [SaleController::class, 'store'])->name('vendor.sales.store');
    Route::put('sales/{id}', [SaleController::class, 'update'])->name('vendor.sales.update');
    Route::delete('sales/{id}', [SaleController::class, 'destroy'])->name('vendor.sales.destroy');

    Route::get('orders', [VendorController::class, 'ShowOrders'])->name('vendor.orders');
    Route::patch('orders/{order}/status', [VendorController::class, 'updateOrderStatus'])->name('vendor.order.status');
    Route::patch('orders/{order}/assign-delivery', [VendorController::class, 'assignDeliveryBoy'])->name('vendor.order.assign.delivery');
    Route::get('users', [VendorController::class, 'ShowUsers'])->name('vendor.users.index');
    Route::patch('users/{user}', [VendorController::class, 'updateManagedUser'])->name('vendor.users.update');
    Route::delete('users/{user}', [VendorController::class, 'destroyManagedUser'])->name('vendor.users.destroy');
    Route::get('customers', [VendorController::class, 'ShowCustomers'])->name('vendor.customers');
    Route::get('delivery-boys', [VendorController::class, 'ShowDeliveryBoys'])->name('vendor.delivery-boys');
    Route::patch('users/{user}/status', [VendorController::class, 'updateUserStatus'])->name('vendor.users.status');
    Route::get('analytics', [VendorController::class, 'ShowAnalytics'])->name('vendor.analytics');
    Route::get('earnings', [VendorController::class, 'ShowEarnings'])->name('vendor.earnings');
    Route::get('settings', [VendorController::class, 'ShowSettings'])->name('vendor.settings');
    Route::put('settings/update', [VendorController::class, 'updateSettings'])->name('vendor.settings.update');

    // -----------------------------------------------------
    // SUPER ADMIN — Categories & Subcategories
    // -----------------------------------------------------
    Route::get('categories', [CategoryController::class, 'index'])->name('vendor.categories');
    Route::post('categories', [CategoryController::class, 'storeCategory'])->name('vendor.categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'updateCategory'])->name('vendor.categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroyCategory'])->name('vendor.categories.destroy');
    Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggleCategoryStatus'])->name('vendor.categories.toggle');

    Route::post('subcategories', [CategoryController::class, 'storeSubcategory'])->name('vendor.subcategories.store');
    Route::put('subcategories/{subcategory}', [CategoryController::class, 'updateSubcategory'])->name('vendor.subcategories.update');
    Route::delete('subcategories/{subcategory}', [CategoryController::class, 'destroySubcategory'])->name('vendor.subcategories.destroy');

    // -----------------------------------------------------
    // SUPER ADMIN — Users (create + password reset + impersonate)
    // -----------------------------------------------------
    Route::post('users', [SuperAdminController::class, 'storeUser'])->name('vendor.users.store');
    Route::patch('users/{user}/password', [SuperAdminController::class, 'resetUserPassword'])->name('vendor.users.password');
    Route::get('users/{user}/impersonate', [SuperAdminController::class, 'impersonate'])->name('vendor.users.impersonate');

    // -----------------------------------------------------
    // SUPER ADMIN — Orders (full edit, delete, payment ops)
    // -----------------------------------------------------
    Route::get('orders/{order}/edit', [SuperAdminController::class, 'editOrder'])->name('vendor.orders.edit');
    Route::put('orders/{order}', [SuperAdminController::class, 'updateOrder'])->name('vendor.orders.update');
    Route::delete('orders/{order}', [SuperAdminController::class, 'destroyOrder'])->name('vendor.orders.destroy');
    Route::patch('orders/{order}/mark-paid', [SuperAdminController::class, 'markOrderPaid'])->name('vendor.orders.paid');
    Route::patch('orders/{order}/refund', [SuperAdminController::class, 'refundOrder'])->name('vendor.orders.refund');

    // -----------------------------------------------------
    // SUPER ADMIN — Product bulk actions
    // -----------------------------------------------------
    Route::post('products/bulk', [SuperAdminController::class, 'bulkProducts'])->name('vendor.products.bulk');

    // -----------------------------------------------------
    // SUPER ADMIN — Site content (logo, favicon, hero, footer, contact)
    // -----------------------------------------------------
    Route::get('site-content', [SuperAdminController::class, 'showContent'])->name('vendor.site.content');
    Route::put('site-content', [SuperAdminController::class, 'updateContent'])->name('vendor.site.content.update');

    // -----------------------------------------------------
    // SUPER ADMIN — System tools (cache, migrate, logs)
    // -----------------------------------------------------
    Route::get('system', [SuperAdminController::class, 'showSystem'])->name('vendor.system');
    Route::post('system/run', [SuperAdminController::class, 'runSystemAction'])->name('vendor.system.run');
});

// Impersonation exit — available to any session that has an impersonator stashed
Route::post('impersonate/stop', [SuperAdminController::class, 'stopImpersonate'])
    ->name('impersonate.stop');
