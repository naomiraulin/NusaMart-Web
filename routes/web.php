<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProductController;

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

    // Lanjut menambahkan route /cart dan /checkout di dalam sini

    // Khusus Seller
    Route::prefix('seller')->middleware('role:SELLER')->group(function () {
        // Route ke dashboard seller yang dibuat di awal tadi diletakkan di sini
    });
});