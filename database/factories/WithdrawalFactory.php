<?php

namespace Database\Factories;

use App\Models\Withdrawal;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithdrawalFactory extends Factory
{
    public function definition(): array
    {
        $status      = fake()->randomElement(['PENDING', 'PROCESSING', 'DONE', 'FAILED']);
        $transferPic = ($status === 'DONE') ? fake()->imageUrl(640, 480, 'receipt', true) : null;

        return [
            'idWithdrawal' => app(IdGeneratorService::class)->generate('WDR', Withdrawal::class, 'idWithdrawal'),
            'nominal'      => fake()->randomFloat(2, 50000, 2000000),
            'serviceCost'  => fake()->randomElement([2500, 6500]),
            'status'       => $status,
            'transferPic'  => $transferPic,
        ];
    }
}