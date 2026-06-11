<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProductItem;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        if ($orders->count() > 0) {
            foreach ($orders as $order) {
                // HANYA ambil item dari produk yang dimiliki oleh toko pada order ini
                $storeProductItems = ProductItem::whereHas('product', function($query) use ($order) {
                    $query->where('idStore', $order->idStore);
                })->with('product')->get();

                // Pastikan toko tersebut punya minimal 1 produk
                if ($storeProductItems->count() > 0) {
                    $maxItems = min(3, $storeProductItems->count());
                    $randomItems = $storeProductItems->random(rand(1, $maxItems));

                    foreach ($randomItems as $item) {
                        $productName = $item->product ? $item->product->productName : 'Produk UMKM Lokal';

                        OrderItem::factory()->create([
                            'idOrder'       => $order->idOrder,
                            'idItem'        => $item->idItem,
                            'nameSnapshot'  => $productName,
                            'priceSnapshot' => $item->price,
                        ]);
                    }
                }
            }
        }
    }
}