<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\PosController;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Halaman Katalog Toko
Route::get('/produk', [CatalogController::class, 'index'])->name('produk.index');

// Halaman Detail Produk
Route::get('/produk/{id}', [CatalogController::class, 'show'])->name('produk.show');

// Cart & Wishlist
Route::get('/cart', [CustomerController::class, 'cart'])->middleware('auth')->name('cart.index');
Route::get('/wishlist', [CustomerController::class, 'wishlist'])->middleware('auth')->name('wishlist.index');

// Checkout
Route::get('/checkout', [CustomerController::class, 'checkout'])->middleware('auth')->name('checkout');
Route::post('/checkout/initiate', [CustomerController::class, 'initiatePayment'])->middleware('auth')->name('checkout.initiate');
Route::post('/checkout/callback', [CustomerController::class, 'paymentCallback'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->name('checkout.callback');

// Transactions
Route::get('/transactions', [CustomerController::class, 'transactions'])->middleware('auth')->name('transactions.index');
Route::get('/transactions/{invoice_number}', [CustomerController::class, 'invoice'])->middleware('auth')->name('transactions.invoice');

// Profil Akun
Route::get('/akun', [CustomerController::class, 'profile'])->middleware('auth')->name('profile');
Route::post('/akun/update', [CustomerController::class, 'updateProfile'])->middleware('auth')->name('profile.update');
Route::get('/dashboard', [CustomerController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Customer Cart Actions
Route::post('/customer/cart/add/{product_id}', [CustomerController::class, 'addToCart'])->middleware('auth')->name('customer.cart.add');
Route::put('/customer/cart/{id}', [CustomerController::class, 'updateCartQty'])->middleware('auth')->name('customer.cart.update');
Route::delete('/customer/cart/{id}', [CustomerController::class, 'removeFromCart'])->middleware('auth')->name('customer.cart.remove');

// Wishlist Actions
Route::post('/customer/wishlist/{product_id}', [CustomerController::class, 'toggleWishlist'])->middleware('auth')->name('customer.wishlist.toggle');
Route::delete('/customer/wishlist/{id}', [CustomerController::class, 'removeFromWishlist'])->middleware('auth')->name('customer.wishlist.delete');

// Buy Now
Route::post('/buy-now/{product_id}', [CustomerController::class, 'buyNow'])->middleware('auth')->name('buy.now');

// Notification Routes
Route::get('/notifikasi', [CustomerController::class, 'broadcasts'])->middleware('auth')->name('notifications.index');
Route::post('/notifikasi/{id}/read', [CustomerController::class, 'markNotificationRead'])->middleware('auth')->name('notifications.read');
Route::post('/notifikasi/read-all', [CustomerController::class, 'markAllNotificationsRead'])->middleware('auth')->name('notifications.read.all');
Route::get('/notifikasi/unread', [CustomerController::class, 'unreadNotifications'])->middleware('auth')->name('notifications.unread');

// AI Chat Routes - No CSRF for guest access
Route::get('/ai-chat', [AiChatController::class, 'chat'])->name('customer.ai-chat');
Route::post('/ai-chat/send', [AiChatController::class, 'send'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class])->name('customer.ai-chat.send');

// Newsletter
Route::post('/newsletter/subscribe', function () {
    return back();
})->name('newsletter.subscribe');

// Kategori
Route::get('/kategori/{slug}', [CatalogController::class, 'byCategory'])->name('kategori.show');

Route::get('/kategori', [CatalogController::class, 'index'])->name('kategori.index');

// Search API
Route::get('/api/search', [CatalogController::class, 'searchAjax'])->name('api.search');

// Promo
Route::get('/promo', [HomeController::class, 'index'])->name('promo');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/api/dashboard-stats', [AdminController::class, 'dashboardStats'])->name('api.dashboard-stats');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/{id}/edit', [AdminController::class, 'getProduct'])->name('products.edit');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{id}/add-stock', [AdminController::class, 'addStock'])->name('products.add-stock');
    Route::post('/products/{id}/reduce-stock', [AdminController::class, 'reduceStock'])->name('products.reduce-stock');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [AdminController::class, 'orderDetails'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users/{id}/assign-tier', [AdminController::class, 'assignTier'])->name('users.assign-tier');

    Route::get('/tiers', [AdminController::class, 'customerTiers'])->name('tiers.index');
    Route::get('/tiers/{id}/edit', [AdminController::class, 'getCustomerTier'])->name('tiers.edit');
    Route::post('/tiers', [AdminController::class, 'storeCustomerTier'])->name('tiers.store');
    Route::put('/tiers/{id}', [AdminController::class, 'updateCustomerTier'])->name('tiers.update');
    Route::delete('/tiers/{id}', [AdminController::class, 'destroyCustomerTier'])->name('tiers.destroy');

    Route::get('/broadcasts', [AdminController::class, 'broadcasts'])->name('broadcasts.index');
    Route::post('/broadcasts/send', [AdminController::class, 'sendBroadcast'])->name('broadcasts.send');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/api/users', [AdminController::class, 'searchUsers'])->name('users.search');

    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [PosController::class, 'searchProduct'])->name('pos.search');
    Route::get('/pos/load-products', [PosController::class, 'loadProducts'])->name('pos.load-products');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/stock-in', [PosController::class, 'showStockForm'])->name('pos.stock-in');
    Route::post('/pos/stock-in', [PosController::class, 'addStock'])->name('pos.stock-in.store');


    });
    // Jalur API untuk menadah sinkronisasi data dari lokal ke cloud
    Route::post('/sync/receive', [CustomerController::class, 'receiveSyncData']);
    Route::get('/sync/pull', [CustomerController::class, 'sendDataToLocal']);