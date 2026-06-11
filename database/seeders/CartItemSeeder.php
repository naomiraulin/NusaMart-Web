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
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idCartItem": "CRI-000001",
            "idCart": "CRT-000001",
            "idItem": "ITM-000003",
            "quantity": 2,
            "isChecked": true
          }
        ]';

        $cartItems = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($cartItems as $item) {
            // Cek apakah idCart dan idItem benar-benar ada di database
            $cartExists = Cart::where('idCart', $item['idCart'])->exists();
            $itemExists = ProductItem::where('idItem', $item['idItem'])->exists();

            // Jika kedua data referensi tersebut ditemukan, baru masukkan ke dalam database
            if ($cartExists && $itemExists) {
                CartItem::create([
                    'idCartItem' => $item['idCartItem'],
                    'idCart'     => $item['idCart'],
                    'idItem'     => $item['idItem'],
                    'quantity'   => $item['quantity'],
                    'isChecked'  => $item['isChecked'],
                ]);
            } else {
                // Opsional: Untuk melihat data mana yang dilewati karena relasinya tidak lengkap
                // echo "Gagal menambah CRI: Cart atau Item tidak ditemukan.\n";
            }
        }
    }
}