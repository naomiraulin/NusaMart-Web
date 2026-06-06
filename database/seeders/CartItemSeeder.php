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

        // Pastikan ada produk sebelum mengisi keranjang
        if ($productItems->count() > 0) {
            foreach ($carts as $cart) {
                // Beri simulasi bahwa 60% user memasukkan barang ke keranjang
                if (rand(1, 100) <= 60) {
                    // Masukkan 1 sampai 4 item berbeda ke dalam keranjang user ini
                    $randomItems = $productItems->random(rand(1, 4));
                    
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