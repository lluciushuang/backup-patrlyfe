<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (home.blade.php)
Route::get('/', function () {
    return view('home');
})->name('home');

// 2. Halaman Katalog Toko (catalog.blade.php)
Route::view('/produk', 'catalog')->name('produk.index');

// 3. Halaman Detail Produk (show.blade.php)
Route::view('/produk/detail', 'show')->name('produk.show');

// ── ROUTING CART & WISHLIST PARTLYFE ──
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', function () { return view('cart.index'); })->name('index');
});

Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', function () { return view('wishlist.index'); })->name('index');
}); 

// 5. Halaman Checkout (checkout.blade.php)
Route::view('/checkout', 'checkout')->name('checkout');

// 6. Halaman Profil Akun (profile.blade.php)
Route::view('/akun', 'profile')->name('akun');

// Route dummy untuk handle post form newsletter biar ga error saat diklik
Route::post('/newsletter/subscribe', function() { return back(); })->name('newsletter.subscribe');
Route::get('/kategori/{slug}', function($slug) { return "Kategori: " . $slug; })->name('kategori.show');
Route::view('/kategori', 'catalog')->name('kategori.index');
Route::view('/promo', 'home')->name('promo');