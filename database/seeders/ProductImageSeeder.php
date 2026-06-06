<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // 1. Buat SATU foto utama (isPrimary = true)
            ProductImage::factory()->create([
                'idProduct' => $product->idProduct,
                'isPrimary' => true,
            ]);

            // 2. Tambahkan 1 hingga 3 foto tambahan secara acak (isPrimary = false)
            ProductImage::factory(rand(1, 3))->create([
                'idProduct' => $product->idProduct,
                'isPrimary' => false,
            ]);
        }
    }
}