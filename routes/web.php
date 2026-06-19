<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Buyer\ProductController;
use App\Http\Controllers\Web\Shared\NotificationController;
use App\Http\Controllers\Web\Seller\DashboardController;
use App\Http\Controllers\Web\Seller\StoreController;
use App\Http\Controllers\Web\Shared\ProfileController;
use App\Http\Controllers\Web\Shared\ChatController;
use App\Http\Controllers\Web\Shared\StoreDetailController;
use App\Http\Controllers\Web\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Web\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Web\Seller\WalletController;
use App\Http\Controllers\Web\Buyer\ReviewController;
use App\Http\Controllers\Web\Admin\VerificationController;

// Homepage & Produk
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/toko/{id}', [StoreDetailController::class, 'show'])->name('store.detail');

// Guest Route
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth Route
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Admin Route
    Route::prefix('admin')->middleware('role:ADMIN')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', function () { 
            return view('admin.dashboard'); 
        })->name('admin.dashboard');

        // Verifications Group
        Route::prefix('verification')->name('admin.verification.')->controller(VerificationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
        });

        // Nanti kalau ada Controller lain (misal UserController), kamu tinggal buat grup baru di sini:
        // Route::prefix('users')->name('admin.users.')->controller(UserController::class)->group(function () { ... });
    });

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/address', [ProfileController::class, 'saveAddress'])->name('profile.address.save');
    Route::put('/profile/address/{idAddress}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');

    // Notifikasi
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::post('/read-all', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::post('/{id}/read', 'markAsRead')->name('notifications.markAsRead');
        Route::get('/unread-count', 'unreadCount')->name('notifications.unreadCount');
    });

    // Chat
    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('chat.index');
        Route::get('/{roomId}', 'show')->name('chat.show');
        Route::post('/{roomId}/send', 'send')->name('chat.send');
        Route::post('/open/{sellerId}', 'openWithSeller')->name('chat.openWithSeller');
    });

    // Cart
    Route::post('/cart/add', [\App\Http\Controllers\Web\Buyer\CartController::class, 'add'])->name('buyer.cart.add');
    Route::get('/cart', [\App\Http\Controllers\Web\Buyer\CartController::class, 'index'])->name('buyer.cart.index');
    Route::put('/cart/{cartItemId}', [\App\Http\Controllers\Web\Buyer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItemId}', [\App\Http\Controllers\Web\Buyer\CartController::class, 'remove'])->name('buyer.cart.remove');

    // Orders Buyer
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('buyer.orders.index');
    Route::get('/orders/{id}', [BuyerOrderController::class, 'show'])->name('buyer.orders.show');
    Route::get('/checkout', [BuyerOrderController::class, 'checkout'])->name('buyer.orders.checkout');
    Route::post('/checkout', [BuyerOrderController::class, 'placeOrder'])->name('buyer.orders.placeOrder');
    Route::post('/orders/{id}/cancel', [BuyerOrderController::class, 'cancel'])->name('buyer.orders.cancel');
    Route::post('/orders/{id}/complete', [BuyerOrderController::class, 'complete'])->name('buyer.orders.complete');
    
    // Beli Sekarang
    Route::post('/checkout/direct', [BuyerOrderController::class, 'directCheckout'])->name('buyer.orders.directCheckout');
    Route::post('/checkout/direct/process', [BuyerOrderController::class, 'placeOrderDirect'])->name('buyer.orders.placeOrderDirect');
    
    // Pembayaran (Transfer Manual)
    Route::get('/orders/{id}/payment', [BuyerOrderController::class, 'payment'])->name('buyer.orders.payment');
    Route::post('/orders/{id}/confirm-payment', [BuyerOrderController::class, 'confirmPayment'])->name('buyer.orders.confirmPayment');
    Route::post('/orders/{id}/confirm-cod', [BuyerOrderController::class, 'confirmCod'])->name('buyer.orders.confirmCod');
    
    // Reviews
    Route::prefix('reviews')->name('buyer.reviews.')->controller(ReviewController::class)->group(function () {
        Route::get('/create/{orderItemId}', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });
    
    // Seller
    Route::prefix('seller')->middleware('role:SELLER')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');
        Route::get('/store', [StoreController::class, 'show'])->name('seller.store.show');
        Route::get('/store/edit', [StoreController::class, 'edit'])->name('seller.store.edit');
        Route::put('/store', [StoreController::class, 'update'])->name('seller.store.update');
        Route::post('/store/verify', [StoreController::class, 'requestVerification'])->name('seller.store.verify');

        Route::get('/products', [\App\Http\Controllers\Web\Seller\ProductController::class, 'index'])->name('seller.products.index');
        Route::get('/products/create', [\App\Http\Controllers\Web\Seller\ProductController::class, 'create'])->name('seller.products.create');
        Route::post('/products', [\App\Http\Controllers\Web\Seller\ProductController::class, 'store'])->name('seller.products.store');
        Route::get('/products/{id}/edit', [\App\Http\Controllers\Web\Seller\ProductController::class, 'edit'])->name('seller.products.edit');
        Route::put('/products/{id}', [\App\Http\Controllers\Web\Seller\ProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\Web\Seller\ProductController::class, 'destroy'])->name('seller.products.destroy');

        Route::prefix('orders')->name('seller.orders.')->controller(SellerOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/confirm', 'confirm')->name('confirm');
            Route::put('/{id}/cancel', 'cancel')->name('cancel');
            // Konfirmasi pengiriman (shipping sudah dibuat saat checkout buyer,
            // seller cukup konfirmasi -> generate resi & shippingDate, status order jadi SHIPPED)
            Route::put('/{orderId}/shipping/confirm', 'confirmShipping')->name('shipping.confirm');
        });
        
        // Update status pengiriman (pakai idShipping, dipanggil dari halaman detail order)
        Route::put('/shipping/{shippingId}/status', [SellerOrderController::class, 'updateShipping'])
            ->name('seller.shipping.updateStatus');

        Route::prefix('wallet')->name('seller.wallet.')->controller(WalletController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/withdraw', 'withdrawForm')->name('withdraw');
            Route::post('/withdraw', 'withdraw')->name('withdraw.store');
            Route::get('/receipt/{withdrawalId}', 'receipt')->name('receipt');
        });
    });
});