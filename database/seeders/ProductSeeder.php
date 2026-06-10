<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua toko
        $stores = Store::all();

        foreach ($stores as $store) {
            // Berikan setiap toko antara 5 sampai 15 produk
            $jumlahProduk = rand(5, 15);
            
            Product::factory($jumlahProduk)->create([
                'idStore' => $store->idStore,
            ]);
        }
    }
}
