<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Buyer\ProductController;
use App\Http\Controllers\Web\Shared\NotificationController;
use App\Http\Controllers\Web\Seller\DashboardController;
use App\Http\Controllers\Web\Seller\StoreController;
use App\Http\Controllers\Web\Shared\ProfileController;
use App\Http\Controllers\Web\Shared\ChatController;

// Homepage - Langsung menampilkan produk
Route::get('/', [ProductController::class, 'index'])->name('home');

// Pencarian Produk (Bisa diakses Guest & Buyer)
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Detail Produk
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.detail');

// Route khusus pengunjung yang BELUM LOGIN (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route khusus pengguna yang SUDAH LOGIN (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifikasi (shared - buyer & seller pakai ini)
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::post('/read-all', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::post('/{id}/read', 'markAsRead')->name('notifications.markAsRead');
        Route::get('/unread-count', 'unreadCount')->name('notifications.unreadCount');
    });

    // Chat (shared - buyer & seller pakai ini)
    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('chat.index');
        Route::get('/{roomId}', 'show')->name('chat.show');
        Route::post('/{roomId}/send', 'send')->name('chat.send');

        // Dipakai buyer untuk mulai chat baru ke seller dari halaman toko/produk
        Route::post('/open/{sellerId}', 'openWithSeller')->name('chat.openWithSeller');
    });

    // --- Cart (PLACEHOLDER) ---
    // TODO: ganti closure ini dengan CartController@index begitu sudah dibuat.
    Route::post('/cart/add', [App\Http\Controllers\Web\Buyer\CartController::class, 'store'])->name('buyer.cart.add');
    Route::get('/cart', [\App\Http\Controllers\Web\Buyer\CartController::class, 'index'])->name('buyer.cart.index');
    Route::put('/cart/{cartItemId}', [\App\Http\Controllers\Web\Buyer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItemId}', [\App\Http\Controllers\Web\Buyer\CartController::class, 'remove'])->name('cart.remove');

    // --- Pesanan Saya / Orders Buyer (PLACEHOLDER) ---
    // TODO: ganti closure ini dengan OrderController@index begitu sudah dibuat.
    Route::get('/orders', function () {
        return view('orders.index'); // TODO: buat view resources/views/orders/index.blade.php
    })->name('buyer.orders.index');

    // Khusus Seller
    Route::prefix('seller')->middleware('role:SELLER')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');

        // Toko
        Route::get('/store', [StoreController::class, 'show'])->name('seller.store.show');
        Route::get('/store/edit', [StoreController::class, 'edit'])->name('seller.store.edit');
        Route::put('/store', [StoreController::class, 'update'])->name('seller.store.update');
        Route::post('/store/verify', [StoreController::class, 'requestVerification'])->name('seller.store.verify');

        // Produk
        Route::get('/products', [\App\Http\Controllers\Web\Seller\ProductController::class, 'index'])->name('seller.products.index');
        Route::get('/products/create', [\App\Http\Controllers\Web\Seller\ProductController::class, 'create'])->name('seller.products.create');
        Route::post('/products', [\App\Http\Controllers\Web\Seller\ProductController::class, 'store'])->name('seller.products.store');
        Route::get('/products/{id}/edit', [\App\Http\Controllers\Web\Seller\ProductController::class, 'edit'])->name('seller.products.edit');
        Route::put('/products/{id}', [\App\Http\Controllers\Web\Seller\ProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\Web\Seller\ProductController::class, 'destroy'])->name('seller.products.destroy');

        // Pesanan
        Route::get('/orders', [\App\Http\Controllers\Web\Seller\OrderController::class, 'index'])->name('seller.orders.index');

        // Wallet
        Route::get('/wallet', [\App\Http\Controllers\Web\Seller\WalletController::class, 'index'])->name('seller.wallet.index');
    });
});