<?php

namespace Database\Factories;

use App\Models\StoreWallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreWalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idWallet' => Str::uuid()->toString(),
            // idStore akan disuntikkan via Seeder
            'activeBalance' => fake()->randomFloat(2, 0, 5000000), // Saldo aktif acak 0 - 5.000.000
            'outstandingBalance' => fake()->randomFloat(2, 0, 1000000), // Saldo tertahan acak 0 - 1.000.000
        ];
    }
}