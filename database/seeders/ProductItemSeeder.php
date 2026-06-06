<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductItemSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Setiap produk memiliki 1 hingga 3 variasi/item
            ProductItem::factory(rand(1, 3))->create([
                'idProduct' => $product->idProduct,
            ]);
        }
    }
}