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
        // Ambil item produk beserta relasi induknya (Product) agar bisa mengambil nama produk
        $productItems = ProductItem::with('product')->get();

        if ($orders->count() > 0 && $productItems->count() > 0) {
            foreach ($orders as $order) {
                // Setiap pesanan berisi 1 hingga 3 macam barang
                $randomItems = $productItems->random(rand(1, 3));
                
                foreach ($randomItems as $item) {
                    // Ambil nama dari relasi tabel Product
                    $productName = $item->product ? $item->product->productName : 'Produk UMKM Lokal';

                    OrderItem::factory()->create([
                        'idOrder' => $order->idOrder,
                        'idItem' => $item->idItem,
                        'nameSnapshot' => $productName,       // Snapshot nama aktual
                        'priceSnapshot' => $item->price,      // Snapshot harga aktual
                    ]);
                }
            }
        }
    }
}