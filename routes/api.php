<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\SellerProductController;

// Public
Route::get('/products',                          [ProductController::class, 'index']);
Route::get('/products/search',                   [ProductController::class, 'search']);
Route::get('/products/{id}',                     [ProductController::class, 'show']);
Route::get('/products/store/{storeId}',          [ProductController::class, 'byStore']);
Route::get('/categories',                        [ProductController::class, 'categories']);
Route::get('/categories/{id}/subcategories',     [ProductController::class, 'subCategories']);

Route::get('/stores', [StoreController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});



Route::get('/stores/{id}', [StoreController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // User
    Route::prefix('user')->group(function () {
        Route::get('/profile',              [UserController::class, 'profile']);
        Route::put('/profile',              [UserController::class, 'update']);

        // Addresses
        Route::get('/addresses',            [UserAddressController::class, 'index']);
        Route::post('/addresses',           [UserAddressController::class, 'store']);
        Route::put('/addresses/{id}',       [UserAddressController::class, 'update']);
        Route::delete('/addresses/{id}',    [UserAddressController::class, 'destroy']);
        Route::put('/addresses/{id}/default', [UserAddressController::class, 'setDefault']);

        // === TAMBAHAN BARU ===
        // Mengambil profil user lain berdasarkan ID (misal: untuk melihat toko seller)
        // Harus diletakkan di paling bawah agar "profile" dan "addresses" tidak terbaca sebagai {id}
        Route::get('/{id}',                 [UserController::class, 'show']);

        // Seller
        Route::prefix('seller')->group(function () {
            Route::get('/store',  [StoreController::class, 'myStore']);
            Route::put('/store',  [StoreController::class, 'update']);
            Route::post('/products',                 [SellerProductController::class, 'store']);
            Route::put('/products/{id}',             [SellerProductController::class, 'update']);
            Route::delete('/products/{id}',          [SellerProductController::class, 'destroy']);
            Route::post('/products/{id}/variations', [SellerProductController::class, 'addVariation']);
        });

    });

});
