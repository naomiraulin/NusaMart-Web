<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\StoreService;
use App\Services\WalletService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private OrderService  $orderService,
        private StoreService  $storeService,
        private WalletService $walletService,
    ) {}

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $recentOrders  = $this->orderService->getByStore($store->idStore);
        $wallet        = $this->walletService->getByStore($store->idStore);
        $statusCounts  = $this->orderService->getStatusCounts($store->idStore); // ← tambah ini

        $stats = [
            'total_orders'   => $recentOrders->total(),
            'active_balance' => $wallet->activeBalance,
            'outstanding'    => $wallet->outstandingBalance,
            'store_rating'   => $store->storeRating ?? 0,
        ];

        return view('seller.dashboard', compact('store', 'recentOrders', 'wallet', 'stats', 'statusCounts')); // ← tambah statusCounts
    }
}