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
        // 1. Simulasikan Uang Masuk (IN) yang referensinya mengarah ke idOrder
        $deliveredOrders = Order::where('orderStatus', 'DELIVERED')->get();
        foreach ($deliveredOrders as $order) {
            $wallet = StoreWallet::where('idStore', $order->idStore)->first();
            
            if ($wallet) {
                WalletTransaction::factory()->create([
                    'idWallet' => $wallet->idWallet,
                    'mutationType' => 'IN',
                    'nominal' => $order->productTotalPrice, 
                    'description' => 'Pendapatan dari pesanan ' . $order->invoiceNumber,
                    'referenceId' => $order->idOrder, // FK ke Order
                ]);
            }
        }

        // 2. Simulasikan Uang Keluar (OUT) yang referensinya mengarah ke idWithdrawal
        $withdrawals = Withdrawal::all();
        foreach ($withdrawals as $withdrawal) {
            WalletTransaction::factory()->create([
                'idWallet' => $withdrawal->idWallet,
                'mutationType' => 'OUT',
                'nominal' => $withdrawal->nominal,
                'description' => 'Pencairan dana ke rekening penjual',
                'referenceId' => $withdrawal->idWithdrawal, // FK ke Withdrawal
            ]);
        }
    }
}