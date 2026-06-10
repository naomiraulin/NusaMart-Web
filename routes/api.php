<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAddressController;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

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
    });

});