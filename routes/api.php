<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\SellerProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AdminReportController;

// =====================
// PUBLIC ROUTES
// =====================

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Products
Route::get('/products',                      [ProductController::class, 'index']);
Route::get('/products/search',               [ProductController::class, 'search']);
Route::get('/products/store/{storeId}',      [ProductController::class, 'byStore']);
Route::get('/products/{id}',                 [ProductController::class, 'show']);

// Categories
Route::get('/categories',                    [ProductController::class, 'categories']);
Route::get('/categories/{id}/subcategories', [ProductController::class, 'subCategories']);

// Stores
Route::get('/stores',                        [StoreController::class, 'index']);
Route::get('/stores/{id}',                   [StoreController::class, 'show']);

// Payment Methods
Route::get('/payments/methods',              [PaymentController::class, 'methods']);

// Shipping
Route::get('/couriers',          [ShippingController::class, 'couriers']);
Route::get('/couriers/{id}',     [ShippingController::class, 'courierDetail']);

// Reviews
Route::get('/reviews/product/{productId}', [ReviewController::class, 'byProduct']);

// Reports - User
Route::prefix('reports')->group(function () {
    Route::get('/',        [ReportController::class, 'index']);
    Route::get('/{id}',    [ReportController::class, 'show']);
    Route::post('/',       [ReportController::class, 'store']);
});

// =====================
// PROTECTED ROUTES
// =====================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Shipping - Buyer
    Route::get('/shipping/order/{orderId}',      [ShippingController::class, 'byOrder']);
    Route::get('/shipping/{id}/tracking',        [ShippingController::class, 'tracking']);

    // User
    Route::prefix('user')->group(function () {
        Route::get('/profile',                    [UserController::class, 'profile']);
        Route::put('/profile',                    [UserController::class, 'update']);
        Route::get('/{id}',                       [UserController::class, 'show']);

        // Addresses
        Route::get('/addresses',                  [UserAddressController::class, 'index']);
        Route::post('/addresses',                 [UserAddressController::class, 'store']);
        Route::put('/addresses/{id}',             [UserAddressController::class, 'update']);
        Route::delete('/addresses/{id}',          [UserAddressController::class, 'destroy']);
        Route::put('/addresses/{id}/default',     [UserAddressController::class, 'setDefault']);
    });

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/',                           [CartController::class, 'index']);
        Route::post('/items',                     [CartController::class, 'addItem']);
        Route::put('/items/{id}/quantity',        [CartController::class, 'updateQuantity']);
        Route::put('/items/{id}/checked',         [CartController::class, 'updateChecked']);
        Route::put('/check-all',                  [CartController::class, 'updateAllChecked']);
        Route::delete('/items/{id}',              [CartController::class, 'deleteItem']);
    });

    // Payments
    Route::prefix('payments')->group(function () {
        Route::get('/order/{orderId}',            [PaymentController::class, 'byOrder']);
        Route::get('/{id}',                       [PaymentController::class, 'show']);
        Route::post('/',                          [PaymentController::class, 'store']);
        Route::put('/{id}/status',                [PaymentController::class, 'updateStatus']);
    });

    // Reviews
    Route::post('/reviews/items',        [ReviewController::class, 'byItems']);
    Route::post('/reviews',              [ReviewController::class, 'store']);

    // Orders - Buyer
    Route::get('/orders',                    [OrderController::class, 'index']);
    Route::get('/orders/{id}',               [OrderController::class, 'show']);
    Route::post('/orders',                   [OrderController::class, 'store']);
    Route::put('/orders/{id}/cancel',        [OrderController::class, 'cancel']);
    Route::get('/orders/{id}/reviewed',      [OrderController::class, 'isReviewed']);

    // Chat
    Route::prefix('chat')->group(function () {
        Route::get('/rooms',                     [ChatController::class, 'rooms']);
        Route::get('/rooms/{id}',                [ChatController::class, 'roomDetail']);
        Route::post('/rooms',                    [ChatController::class, 'getOrCreateRoom']);
        Route::get('/rooms/{id}/messages',       [ChatController::class, 'messages']);
        Route::post('/rooms/{id}/messages',      [ChatController::class, 'sendMessage']);
        Route::put('/rooms/{id}/read',           [ChatController::class, 'markAsRead']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',                [NotificationController::class, 'index']);
        Route::get('/{id}',            [NotificationController::class, 'show']);
        Route::put('/read-all',        [NotificationController::class, 'markAllAsRead']);
        Route::put('/{id}/read',       [NotificationController::class, 'markAsRead']);
    });

    // Seller
    Route::prefix('seller')->group(function () {
        // Store
        Route::get('/store',                      [StoreController::class, 'myStore']);
        Route::put('/store',                      [StoreController::class, 'update']);

        // Products
        Route::post('/products',                  [SellerProductController::class, 'store']);
        Route::put('/products/{id}',              [SellerProductController::class, 'update']);
        Route::delete('/products/{id}',           [SellerProductController::class, 'destroy']);
        Route::post('/products/{id}/variations',  [SellerProductController::class, 'addVariation']);

        // Wallet
        Route::get('/wallet',                     [WalletController::class, 'index']);
        Route::get('/wallet/transactions',        [WalletController::class, 'transactions']);
        Route::get('/wallet/withdrawals',         [WalletController::class, 'withdrawals']);
        Route::post('/wallet/withdraw',           [WalletController::class, 'withdraw']);
        
        // Shipping
        Route::post('/shipping',                 [ShippingController::class, 'store']);
        Route::put('/shipping/{id}/status',      [ShippingController::class, 'updateStatus']);
        Route::post('/shipping/{id}/tracking',   [ShippingController::class, 'addTracking']);

        // Orders
        Route::get('/orders',                [OrderController::class, 'sellerOrders']);
        Route::put('/orders/{id}/status',    [OrderController::class, 'updateStatus']);
    });

    // Admin
    Route::prefix('admin')->group(function () {
        Route::put('/withdrawals/{id}/status',    [WalletController::class, 'updateWithdrawalStatus']);
        Route::put('/reviews/{id}/hide', [ReviewController::class, 'hide']);
        Route::get('/reports',                   [AdminReportController::class, 'index']);
        Route::get('/reports/{id}',              [AdminReportController::class, 'show']);
        Route::put('/reports/{id}/status',       [AdminReportController::class, 'updateStatus']);
    });
});