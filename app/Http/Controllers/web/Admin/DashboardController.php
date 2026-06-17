<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Halaman dashboard admin — statistik keseluruhan platform.
     */
    public function index(): View
    {
        $stats = [
            'total_users'    => User::count(),
            'total_sellers'  => User::where('role', 'SELLER')->count(),
            'total_buyers'   => User::where('role', 'BUYER')->count(),
            'total_products' => Product::count(),
            'total_stores'   => Store::count(),
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('orderStatus', 'PENDING')->count(),
        ];

        $recentOrders = Order::with(['store', 'user'])
            ->latest('orderDate')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}