<?php

namespace Database\Factories;

use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariationFactory extends Factory
{
    public function definition(): array
    {
        // Tentukan tipe variasinya secara acak
        $type = fake()->randomElement(['Warna', 'Ukuran', 'Rasa']);
        
        // Sesuaikan value dengan tipenya
        $value = match($type) {
            'Warna' => fake()->safeColorName(),
            'Ukuran' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'Rasa' => fake()->randomElement(['Original', 'Pedas Manis', 'Keju', 'Balado']),
            default => 'Standard',
        };

        return [
            'idVariation' => Str::uuid()->toString(),
            // idItem akan diisi otomatis lewat Seeder
            'typeVariation' => $type,
            'value' => ucfirst($value),
        ];
    }
}