<?php

namespace Database\Seeders;

use App\Models\StoreWallet;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    public function run(): void
    {
        $wallets = StoreWallet::all();

        foreach ($wallets as $wallet) {
            // Beri simulasi 50% toko sudah pernah melakukan penarikan dana
            if (rand(1, 100) <= 50) {
                // Buat 1 sampai 3 riwayat penarikan per toko
                Withdrawal::factory(rand(1, 3))->create([
                    'idWallet' => $wallet->idWallet,
                ]);
            }
        }
    }
}