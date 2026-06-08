<?php

namespace Database\Factories;

use App\Models\ProductVariation;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariationFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['Warna', 'Ukuran', 'Rasa']);

        $value = match($type) {
            'Warna'  => fake()->safeColorName(),
            'Ukuran' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'Rasa'   => fake()->randomElement(['Original', 'Pedas Manis', 'Keju', 'Balado']),
            default  => 'Standard',
        };

        return [
            'idVariation'   => app(IdGeneratorService::class)->generate('VAR', ProductVariation::class, 'idVariation'),
            'typeVariation' => $type,
            'value'         => ucfirst($value),
        ];
    }
}