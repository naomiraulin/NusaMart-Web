<?php

namespace Database\Factories;

use App\Models\UserAddress;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idAddress'       => app(IdGeneratorService::class)->generate('ADR', UserAddress::class, 'idAddress'),
            'label'           => fake()->randomElement(['Rumah', 'Kantor', 'Kos', 'Toko']),
            'receiver'        => fake()->name(),
            'phone'           => fake()->phoneNumber(),
            'completeAddress' => fake()->streetAddress(),
            'city'            => fake()->city(),
            'province'        => fake()->state(),
            'postalCode'      => fake()->postcode(),
            'isDefault'       => false,
        ];
    }
}