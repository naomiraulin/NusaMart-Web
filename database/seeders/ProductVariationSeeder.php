<?php

namespace Database\Seeders;

use App\Models\ProductItem;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    public function run(): void
    {
        $productItems = ProductItem::all();

        foreach ($productItems as $item) {
            // Berikan 1 hingga 2 variasi (misal: Warna dan Ukuran) untuk SKU ini
            ProductVariation::factory(rand(1, 2))->create([
                'idItem' => $item->idItem,
            ]);
        }
    }
}