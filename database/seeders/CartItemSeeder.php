<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil seluruh data keranjang dan item produk yang ada di database
        $carts = Cart::all();
        $productItems = ProductItem::all();

        // Pastikan ada cart dan product item sebelum melakukan proses
        if ($carts->count() > 0 && $productItems->count() > 0) {
            $counter = 1;

            foreach ($carts as $cart) {
                // Tentukan jumlah macam produk acak untuk tiap keranjang (misal: 1 sampai 4 macam produk)
                $maxItems = min(4, $productItems->count());
                $randomItems = $productItems->random(rand(1, $maxItems));

                foreach ($randomItems as $item) {
                    CartItem::create([
                        // Generate ID unik untuk cart item (CRI-000001, CRI-000002, dst.)
                        'idCartItem' => 'CRI-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                        
                        // Pasangkan dengan keranjang dan item produk yang valid
                        'idCart'     => $cart->idCart,
                        'idItem'     => $item->idItem,
                        
                        // Berikan kuantitas acak (misal user memasukkan 1 sampai 5 barang yang sama)
                        'quantity'   => rand(1, 5),
                        
                        // Acak status isChecked (true atau false) layaknya user yang memilih barang untuk di-checkout
                        'isChecked'  => (bool) rand(0, 1),
                    ]);
                }
            }
        }
    }
}