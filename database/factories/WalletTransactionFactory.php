<?php

namespace Database\Factories;

use App\Models\WalletTransaction;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['IN', 'OUT']);

        return [
            'idTransaction' => app(IdGeneratorService::class)->generate('WTR', WalletTransaction::class, 'idTransaction'),
            'mutationType'  => $type,
            'nominal'       => fake()->randomFloat(2, 50000, 1000000),
            'description'   => ($type === 'IN') ? 'Dana penjualan masuk' : 'Penarikan dana toko',
            'referenceId'   => null, // diisi valid via Seeder
        ];
    }
}