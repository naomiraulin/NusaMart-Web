<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idStore' => Str::uuid()->toString(),
            // idSeller akan diisi oleh Seeder
            'name' => fake()->company() . ' ' . fake()->randomElement(['Store', 'Shop', 'Mart']),
            'description' => fake()->paragraph(),
            'logoURL' => 'https://via.placeholder.com/150', // Teks URL gambar
            'location' => fake()->address(),
            'urlLocation' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
            'storeRating' => fake()->randomFloat(1, 1, 5), // Acak dari 1.0 sampai 5.0
            'isActive' => fake()->boolean(90), // 90% kemungkinan aktif
        ];
    }
}