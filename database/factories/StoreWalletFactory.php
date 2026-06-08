<?php

namespace Database\Factories;

use App\Models\StoreWallet;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreWalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idWallet'           => app(IdGeneratorService::class)->generate('WAL', StoreWallet::class, 'idWallet'),
            'activeBalance'      => fake()->randomFloat(2, 0, 5000000),
            'outstandingBalance' => fake()->randomFloat(2, 0, 1000000),
        ];
    }
}