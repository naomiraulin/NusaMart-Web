<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Buyer\ProductController;
use App\Http\Controllers\Web\Shared\NotificationController;
use App\Http\Controllers\Web\Seller\DashboardController;
use App\Http\Controllers\Web\Seller\StoreController;
use App\Http\Controllers\Web\Shared\ProfileController;

// Homepage - Langsung menampilkan produk
Route::get('/', [ProductController::class, 'index'])->name('home');

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

    // Lanjut menambahkan route /cart dan /checkout di dalam sini

    // Khusus Seller
    Route::prefix('seller')->middleware('role:SELLER')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');
        
        // Toko
        Route::get('/store', [StoreController::class, 'show'])->name('seller.store.show');
        
        // Produk
        Route::get('/products', [\App\Http\Controllers\Web\Seller\ProductController::class, 'index'])->name('seller.products.index');
        
        // Pesanan
        Route::get('/orders', [\App\Http\Controllers\Web\Seller\OrderController::class, 'index'])->name('seller.orders.index');
        
        // Wallet
        Route::get('/wallet', [\App\Http\Controllers\Web\Seller\WalletController::class, 'index'])->name('seller.wallet.index');
        
        // Chat
        Route::get('/chat', [\App\Http\Controllers\Web\Shared\ChatController::class, 'index'])->name('chat.index');
    });
});