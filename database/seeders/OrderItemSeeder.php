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
        $productItems = ProductItem::with('product')->get();

        if ($orders->count() > 0 && $productItems->count() > 0) {
            foreach ($orders as $order) {
                $maxItems = min(3, $productItems->count());
                $randomItems = $productItems->random(rand(1, $maxItems));

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