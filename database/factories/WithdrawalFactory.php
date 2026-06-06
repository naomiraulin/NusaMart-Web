<?php

namespace Database\Factories;

use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WithdrawalFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'PROCESSING', 'DONE', 'FAILED']);
        
        // Bukti transfer hanya ada jika statusnya DONE
        $transferPic = ($status === 'DONE') ? fake()->imageUrl(640, 480, 'receipt', true) : null;

        return [
            'idWithdrawal' => Str::uuid()->toString(),
            // idWallet diisi via Seeder
            'nominal' => fake()->randomFloat(2, 50000, 2000000), // Penarikan 50rb - 2jt
            'serviceCost' => fake()->randomElement([2500, 6500]), // Biaya admin standar transfer antar bank
            'status' => $status,
            'transferPic' => $transferPic,
        ];
    }
}