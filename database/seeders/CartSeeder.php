<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idCart": "CRT-000001",
            "idUser": "BYR-000001"
          }
        ]';

        $carts = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($carts as $cart) {
            // Cek apakah idUser tersebut (contoh: BYR-000001) benar-benar ada di database
            $userExists = User::where('idUser', $cart['idUser'])->exists();

            // Jika user ditemukan, baru buatkan cart-nya untuk mencegah error relasi (foreign key constraint)
            if ($userExists) {
                Cart::create([
                    'idCart' => $cart['idCart'],
                    'idUser' => $cart['idUser'],
                ]);
            } else {
                // Opsional: Kamu bisa mencetak pesan di terminal jika ingin tahu ada data yang terlewat
                // echo "User dengan ID {$cart['idUser']} tidak ditemukan. Keranjang dilewati.\n";
            }
        }
    }
}