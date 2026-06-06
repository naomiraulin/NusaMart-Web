<?php

namespace Database\Factories;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idAddress' => Str::uuid()->toString(),
            // idUser akan diisi otomatis lewat Seeder
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Kos', 'Toko']),
            'receiver' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'completeAddress' => fake()->streetAddress(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'postalCode' => fake()->postcode(),
            'isDefault' => false, // Default awal dibuat false, nanti diatur di seeder
        ];
    }
}