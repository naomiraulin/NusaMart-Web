<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private StoreService  $storeService,
    ) {}

    /**
     * Halaman wallet & riwayat transaksi.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user         = Auth::user();
        $store        = $this->storeService->getBySeller($user->idUser);
        $wallet       = $this->walletService->getByStore($store->idStore);
        $transactions = $this->walletService->getTransactions($store->idStore);

        return view('seller.wallet.index', compact('wallet', 'transactions', 'store'));
    }

    /**
     * Form request penarikan saldo.
     */
    public function withdrawForm(): View
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $store  = $this->storeService->getBySeller($user->idUser);
        $wallet = $this->walletService->getByStore($store->idStore);

        return view('seller.wallet.withdraw', compact('wallet', 'store'));
    }

    /**
     * Proses request penarikan saldo.
     * Setelah berhasil, simpan URL receipt ke transferPic lalu redirect ke receipt.
     */
    public function withdraw(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $withdrawal = $this->walletService->requestWithdrawal(
            $store->idStore,
            $request->input('amount'),
        );

        // Simpan URL receipt ke transferPic
        $receiptUrl = route('seller.wallet.receipt', $withdrawal->idWithdrawal);
        $withdrawal->update(['transferPic' => $receiptUrl]);

        return redirect()->route('seller.wallet.receipt', $withdrawal->idWithdrawal)
            ->with('success', 'Permintaan penarikan berhasil dikirim.');
    }

    /**
     * Halaman bukti penarikan — bisa diprint.
     */
    public function receipt(string $withdrawalId): View
    {
        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $store      = $this->storeService->getBySeller($user->idUser);
        $withdrawal = $this->walletService->getWithdrawalById($withdrawalId);
        $wallet     = $this->walletService->getByStore($store->idStore);

        return view('seller.wallet.receipt', compact('withdrawal', 'store', 'wallet'));
    }
}