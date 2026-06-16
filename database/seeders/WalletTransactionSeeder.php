<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Withdrawal;
use App\Models\StoreWallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $counter = 1;

        // 1. Simulasikan Uang Masuk (IN) yang referensinya mengarah ke idOrder
        $deliveredOrders = Order::where('orderStatus', 'DELIVERED')->get();
        foreach ($deliveredOrders as $order) {
            $wallet = StoreWallet::where('idStore', $order->idStore)->first();
            
            if ($wallet) {
                WalletTransaction::create([
                    // Generate ID Transaksi berurutan (WTR-000001, WTR-000002, dst)
                    'idTransaction' => 'WTR-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                    'idWallet'      => $wallet->idWallet,
                    'mutationType'  => 'IN',
                    'nominal'       => (float) $order->productTotalPrice, 
                    
                    // Menyesuaikan format deskripsi dengan JSON yang kamu berikan
                    'description'   => 'Pembayaran Pesanan ' . $order->idOrder, 
                    'referenceId'   => $order->idOrder, // FK ke Order
                ]);
            }
        }

        // 2. Simulasikan Uang Keluar (OUT) yang referensinya mengarah ke idWithdrawal
        $withdrawals = Withdrawal::all();
        foreach ($withdrawals as $withdrawal) {
            WalletTransaction::create([
                // Lanjutkan ID Transaksi dari counter terakhir
                'idTransaction' => 'WTR-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                'idWallet'      => $withdrawal->idWallet,
                'mutationType'  => 'OUT',
                'nominal'       => (float) $withdrawal->nominal,
                'description'   => 'Pencairan dana ke rekening penjual',
                'referenceId'   => $withdrawal->idWithdrawal, // FK ke Withdrawal
            ]);
        }
    }
}