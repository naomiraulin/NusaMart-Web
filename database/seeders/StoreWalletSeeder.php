<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreWallet;
use Illuminate\Database\Seeder;

class StoreWalletSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data toko yang ada di database
        $stores = Store::all();

        if ($stores->count() > 0) {
            $counter = 1;

            foreach ($stores as $store) {
                // Simulasi saldo acak agar data di aplikasi terlihat realistis (kelipatan 50.000)
                // Misalnya: saldo aktif dari 0 hingga 1.000.000, saldo tertunda dari 0 hingga 500.000
                $simulatedActiveBalance = rand(0, 20) * 50000;
                $simulatedOutstandingBalance = rand(0, 10) * 50000;

                StoreWallet::create([
                    // Generate ID Wallet berurutan (WAL-000001, WAL-000002, dst)
                    'idWallet'           => 'WAL-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                    
                    // Tempelkan ke ID Store yang valid
                    'idStore'            => $store->idStore,
                    
                    // Masukkan struktur saldo sesuai dengan format JSON
                    'activeBalance'      => (float) $simulatedActiveBalance,
                    'outstandingBalance' => (float) $simulatedOutstandingBalance,
                ]);
            }
        }
    }
}