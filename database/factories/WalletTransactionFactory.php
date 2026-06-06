<?php

namespace Database\Factories;

use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WalletTransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['IN', 'OUT']);
        
        return [
            'idTransaction' => Str::uuid()->toString(),
            // idWallet, nominal, description, dan referenceId akan diganti oleh Seeder agar datanya valid
            'mutationType' => $type,
            'nominal' => fake()->randomFloat(2, 50000, 1000000),
            'description' => ($type === 'IN') ? 'Dana penjualan masuk' : 'Penarikan dana toko',
            'referenceId' => Str::uuid()->toString(), 
        ];
    }
}