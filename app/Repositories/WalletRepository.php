<?php

namespace App\Repositories;

use App\Models\StoreWallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Pagination\LengthAwarePaginator;

class WalletRepository
{
    /**
     * Ambil wallet milik store.
     */
    public function findByStore(string $storeId): ?StoreWallet
    {
        return StoreWallet::where('idStore', $storeId)->first();
    }

    /**
     * Buat wallet baru saat store dibuat.
     */
    public function create(string $storeId): StoreWallet
    {
        return StoreWallet::create([
            'idStore'            => $storeId,
            'activeBalance'      => 0,
            'outstandingBalance' => 0,
        ]);
    }

    /**
     * Update saldo wallet.
     */
    public function updateBalance(string $id, float $active, float $outstanding): StoreWallet
    {
        $wallet = StoreWallet::where('idWallet', $id)->firstOrFail();
        $wallet->update([
            'activeBalance'      => $active,
            'outstandingBalance' => $outstanding,
        ]);

        return $wallet->fresh();
    }

    /**
     * Tambah catatan transaksi wallet.
     */
    public function addTransaction(array $data): WalletTransaction
    {
        // Generate idTransaction kalau belum ada di data
        if (!isset($data['idTransaction'])) {
            $data['idTransaction'] = app(\App\Services\IdGeneratorService::class)
                ->generate('WTR', WalletTransaction::class, 'idTransaction');
        }

        return WalletTransaction::create($data);
    }

    /**
     * Ambil riwayat transaksi wallet.
     */
    public function getTransactions(string $walletId): LengthAwarePaginator
    {
        return WalletTransaction::where('idWallet', $walletId)
            ->orderBy('createAt', 'desc')
            ->paginate(15);
    }

    /**
     * Buat request withdrawal.
     */
    public function createWithdrawal(array $data): Withdrawal
    {
        return Withdrawal::create($data);
    }

    /**
     * Update status withdrawal.
     */
    public function updateWithdrawalStatus(string $id, string $status): Withdrawal
    {
        $withdrawal = Withdrawal::where('idWithdrawal', $id)->firstOrFail();
        $withdrawal->update(['status' => $status]);

        return $withdrawal->fresh();
    }
}