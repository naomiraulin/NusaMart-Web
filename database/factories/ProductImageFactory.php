<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idImage' => Str::uuid()->toString(),
            // idProduct diisi dari Seeder
            'imageURL' => fake()->imageUrl(640, 480, 'products', true), // Menggunakan gambar kategori produk
            'isPrimary' => false, // Default false, nanti diset true 1x di Seeder
        ];
    }
}