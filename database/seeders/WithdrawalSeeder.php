<?php

namespace Database\Seeders;

use App\Models\StoreWallet;
use App\Models\Withdrawal;
use App\Services\IdGeneratorService;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    public function run(): void
    {
        $idGenerator = app(IdGeneratorService::class);
        $wallets = StoreWallet::all();

        foreach ($wallets as $wallet) {
            if (rand(1, 100) <= 50) {
                $jumlah = rand(1, 3);
                for ($i = 0; $i < $jumlah; $i++) {
                    $status      = fake()->randomElement(['PENDING', 'PROCESSING', 'DONE', 'FAILED']);
                    $transferPic = ($status === 'DONE') ? fake()->imageUrl(640, 480, 'receipt', true) : null;

                    Withdrawal::create([
                        'idWithdrawal' => $idGenerator->generate('WDR', Withdrawal::class, 'idWithdrawal'),
                        'idWallet'     => $wallet->idWallet,
                        'nominal'      => fake()->randomFloat(2, 50000, 2000000),
                        'serviceCost'  => fake()->randomElement([2500, 6500]),
                        'status'       => $status,
                        'transferPic'  => $transferPic,
                    ]);
                }
            }
        }
    }
}