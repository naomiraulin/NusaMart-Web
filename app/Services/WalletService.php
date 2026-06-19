<?php

namespace App\Services;

use App\Models\StoreWallet;
use App\Models\Withdrawal;
use App\Repositories\WalletRepository;
use App\Services\IdGeneratorService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletService
{
    public function __construct(
        private WalletRepository   $walletRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil wallet store.
     */
    public function getByStore(string $storeId): StoreWallet
    {
        $wallet = $this->walletRepository->findByStore($storeId);

        if (!$wallet) {
            abort(404, 'Wallet tidak ditemukan.');
        }

        return $wallet;
    }

    /**
     * Ambil riwayat transaksi wallet.
     */
    public function getTransactions(string $storeId): LengthAwarePaginator
    {
        $wallet = $this->getByStore($storeId);

        return $this->walletRepository->getTransactions($wallet->idWallet);
    }

    /**
     * Ambil detail withdrawal berdasarkan ID.
     */
    public function getWithdrawalById(string $withdrawalId): Withdrawal
    {
        $withdrawal = Withdrawal::where('idWithdrawal', $withdrawalId)->first();

        if (!$withdrawal) {
            abort(404, 'Data penarikan tidak ditemukan.');
        }

        return $withdrawal;
    }

    /**
     * Pindahkan saldo dari outstanding ke active
     * setelah order berstatus DELIVERED.
     */
    public function releaseBalance(string $storeId, float $amount, string $orderId): void
    {
        DB::transaction(function () use ($storeId, $amount, $orderId) {
            $wallet = $this->getByStore($storeId);

            $this->walletRepository->updateBalance(
                $wallet->idWallet,
                $wallet->activeBalance + $amount,
                max(0, $wallet->outstandingBalance - $amount),
            );

            $this->walletRepository->addTransaction([
                'idWallet'     => $wallet->idWallet,
                'mutationType' => 'IN',
                'nominal'      => $amount,
                'description'  => 'Dana cair setelah pesanan diterima.',
                'referenceId'  => $orderId,
            ]);
        });
    }

    /**
     * Request penarikan saldo oleh seller.
     * activeBalance hanya berisi dana dari pesanan yang sudah DELIVERED.
     */
    public function requestWithdrawal(string $storeId, float $amount): Withdrawal
    {
        return DB::transaction(function () use ($storeId, $amount) {
            $wallet = $this->getByStore($storeId);

            if ($wallet->activeBalance < $amount) {
                throw ValidationException::withMessages([
                    'amount' => ['Saldo tidak mencukupi untuk penarikan ini.'],
                ]);
            }

            // 1. Kurangi active balance
            $this->walletRepository->updateBalance(
                $wallet->idWallet,
                $wallet->activeBalance - $amount,
                $wallet->outstandingBalance,
            );

            // 2. Buat withdrawal dulu — supaya ada idWithdrawal
            $withdrawal = $this->walletRepository->createWithdrawal([
                'idWithdrawal' => $this->idGenerator->generate('WDR', Withdrawal::class, 'idWithdrawal'),
                'idWallet'     => $wallet->idWallet,
                'nominal'      => $amount,
                'serviceCost'  => 0,
                'status'       => 'PENDING',
            ]);

            // 3. Catat transaksi dengan referenceId dari withdrawal
            $this->walletRepository->addTransaction([
                'idWallet'     => $wallet->idWallet,
                'mutationType' => 'OUT',
                'nominal'      => $amount,
                'description'  => 'Permintaan penarikan saldo.',
                'referenceId'  => $withdrawal->idWithdrawal,
            ]);

            return $withdrawal;
        });
    }

    /**
     * Update status withdrawal (oleh admin).
     */
    public function updateWithdrawalStatus(string $withdrawalId, string $status): Withdrawal
    {
        return $this->walletRepository->updateWithdrawalStatus($withdrawalId, $status);
    }
}