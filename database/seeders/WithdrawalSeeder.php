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

        if ($wallets->count() > 0) {
            $counter = 1;

            foreach ($wallets as $wallet) {
                // Simulasi: 50% kemungkinan toko pernah melakukan penarikan dana
                if (rand(1, 100) <= 50) {
                    $jumlah = rand(1, 3); // Tiap toko melakukan 1 sampai 3 kali penarikan

                    for ($i = 0; $i < $jumlah; $i++) {
                        $statusOptions = ['PENDING', 'PROCESSING', 'DONE', 'FAILED'];
                        $status = $statusOptions[array_rand($statusOptions)];

                        // Jika status DONE, beri URL gambar struk. Jika tidak, kosongkan.
                        // Menggunakan layanan placeholder gambar statis agar proses seeder lebih cepat
                        $transferPic = ($status === 'DONE') ? 'https://dummyimage.com/640x480/cccccc/000000&text=Receipt+Transfer' : null;

                        // Nominal penarikan kelipatan Rp 50.000 agar rapi (antara 50.000 sampai 2.000.000)
                        $nominal = rand(1, 40) * 50000;

                        // Biaya admin bank (misal: 2500 untuk bank sama, 6500 untuk antar bank)
                        $serviceCosts = [2500, 6500];
                        $serviceCost = $serviceCosts[array_rand($serviceCosts)];

                        Withdrawal::create([
                            // Generate ID Penarikan (WDR-000001, WDR-000002, dst)
                            // Catatan: Jika aplikasimu wajib pakai $idGenerator->generate(), silakan diganti kembali.
                            'idWithdrawal' => 'WDR-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                            'idWallet'     => $wallet->idWallet,
                            'nominal'      => (float) $nominal,
                            'serviceCost'  => (float) $serviceCost,
                            'status'       => $status,
                            'transferPic'  => $transferPic,
                        ]);
                    }
                }
            }
        }
    }
}