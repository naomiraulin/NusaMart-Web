<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreWallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/seller/wallet → ambil wallet toko
    public function index(Request $request)
    {
        $store = Store::where('idSeller', $request->user()->idUser)->firstOrFail();
        $wallet = StoreWallet::where('idStore', $store->idStore)->firstOrFail();

        return response()->json($wallet);
    }

    // GET /api/seller/wallet/transactions → riwayat transaksi wallet
    public function transactions(Request $request)
    {
        $store = Store::where('idSeller', $request->user()->idUser)->firstOrFail();
        $wallet = StoreWallet::where('idStore', $store->idStore)->firstOrFail();

        $transactions = WalletTransaction::where('idWallet', $wallet->idWallet)
            ->orderByDesc('createAt')
            ->get();

        return response()->json($transactions);
    }

    // POST /api/seller/wallet/withdraw → buat penarikan dana
    public function withdraw(Request $request)
    {
        $request->validate([
            'nominal'     => 'required|numeric|min:50000',
            'serviceCost' => 'required|numeric',
        ]);

        $store = Store::where('idSeller', $request->user()->idUser)->firstOrFail();
        $wallet = StoreWallet::where('idStore', $store->idStore)->firstOrFail();

        $totalDeduction = $request->nominal + $request->serviceCost;

        // Validasi saldo
        if ($wallet->activeBalance < $totalDeduction) {
            return response()->json([
                'message' => 'Saldo aktif tidak mencukupi untuk melakukan penarikan.'
            ], 422);
        }

        // Potong saldo aktif
        $wallet->update([
            'activeBalance' => $wallet->activeBalance - $totalDeduction,
        ]);

        // Buat withdrawal
        $withdrawal = Withdrawal::create([
            'idWithdrawal' => $this->idGenerator->generate('WDR', Withdrawal::class, 'idWithdrawal'),
            'idWallet'     => $wallet->idWallet,
            'nominal'      => $request->nominal,
            'serviceCost'  => $request->serviceCost,
            'status'       => 'PENDING',
            'transferPic'  => null,
        ]);

        // Catat mutasi OUT
        WalletTransaction::create([
            'idTransaction' => $this->idGenerator->generate('WTR', WalletTransaction::class, 'idTransaction'),
            'idWallet'      => $wallet->idWallet,
            'mutationType'  => 'OUT',
            'nominal'       => $totalDeduction,
            'description'   => 'Penarikan Dana',
            'referenceId'   => $withdrawal->idWithdrawal,
        ]);

        return response()->json([
            'message'    => 'Penarikan berhasil diajukan',
            'withdrawal' => $withdrawal,
        ], 201);
    }

    // GET /api/seller/wallet/withdrawals → riwayat penarikan
    public function withdrawals(Request $request)
    {
        $store = Store::where('idSeller', $request->user()->idUser)->firstOrFail();
        $wallet = StoreWallet::where('idStore', $store->idStore)->firstOrFail();

        $withdrawals = Withdrawal::where('idWallet', $wallet->idWallet)
            ->orderByDesc('createAt')
            ->get();

        return response()->json($withdrawals);
    }

    // PUT /api/admin/withdrawals/{id}/status → update status withdrawal (Admin)
    public function updateWithdrawalStatus(Request $request, string $id)
    {
        $request->validate([
            'status'      => 'required|in:PENDING,PROCESSING,DONE,FAILED',
            'transferPic' => 'sometimes|nullable|string',
        ]);

        $withdrawal = Withdrawal::where('idWithdrawal', $id)->firstOrFail();
        $wallet = StoreWallet::where('idWallet', $withdrawal->idWallet)->firstOrFail();

        $withdrawal->update([
            'status'      => $request->status,
            'transferPic' => $request->transferPic ?? $withdrawal->transferPic,
        ]);

        // Kalau FAILED, kembalikan dana ke active balance
        if ($request->status === 'FAILED') {
            $totalRefund = $withdrawal->nominal + $withdrawal->serviceCost;

            $wallet->update([
                'activeBalance' => $wallet->activeBalance + $totalRefund,
            ]);

            // Catat mutasi IN (pengembalian dana)
            WalletTransaction::create([
                'idTransaction' => $this->idGenerator->generate('WTR', WalletTransaction::class, 'idTransaction'),
                'idWallet'      => $wallet->idWallet,
                'mutationType'  => 'IN',
                'nominal'       => $totalRefund,
                'description'   => 'Pengembalian Dana Penarikan Gagal',
                'referenceId'   => $id,
            ]);
        }

        return response()->json([
            'message'    => 'Status withdrawal berhasil diupdate',
            'withdrawal' => $withdrawal,
        ]);
    }
}