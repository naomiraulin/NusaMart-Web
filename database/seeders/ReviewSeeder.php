<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua Order yang statusnya sudah DELIVERED beserta itemnya
        $deliveredOrders = Order::with('orderItems')->where('orderStatus', 'DELIVERED')->get();

        foreach ($deliveredOrders as $order) {
            // Asumsikan pembeli hanya me-review sekitar 70% dari barang yang mereka beli
            if (rand(1, 100) <= 70) {
                foreach ($order->orderItems as $item) {
                    Review::factory()->create([
                        'idOrderItem' => $item->idOrderItem,
                        'idUser' => $order->idUser, // Pembeli yang melakukan transaksi
                    ]);
                }
            }
        }
    }
}