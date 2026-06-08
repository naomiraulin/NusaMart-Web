<?php

namespace Database\Factories;

use App\Models\Store;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idStore'     => app(IdGeneratorService::class)->generate('STR', Store::class, 'idStore'),
            'name'        => fake()->company() . ' ' . fake()->randomElement(['Store', 'Shop', 'Mart']),
            'description' => fake()->paragraph(),
            'logoURL'     => null,
            'location'    => fake()->address(),
            'urlLocation' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
            'storeRating' => fake()->randomFloat(1, 1, 5),
            'isActive'    => fake()->boolean(90),
            'createAt'    => now(),
            'updateAt'    => now(),
        ];
    }
}