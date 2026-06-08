<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\ProductItem;
use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $carts = Cart::all();
        $productItems = ProductItem::all();

        if ($productItems->count() > 0) {
            foreach ($carts as $cart) {
                if (rand(1, 100) <= 60) {
                    // Ambil max sesuai jumlah produk yang tersedia
                    $maxItems = min(4, $productItems->count());
                    $randomItems = $productItems->random(rand(1, $maxItems));

                    foreach ($randomItems as $item) {
                        CartItem::factory()->create([
                            'idCart' => $cart->idCart,
                            'idItem' => $item->idItem,
                        ]);
                    }
                }
            }
        }
    }
}