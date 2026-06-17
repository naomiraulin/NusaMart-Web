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

        return view('seller.wallet.index', compact('wallet', 'transactions'));
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

        return view('seller.wallet.withdraw', compact('wallet'));
    }

    /**
     * Proses request penarikan saldo.
     */
    public function withdraw(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $this->walletService->requestWithdrawal($store->idStore, $request->input('amount'));

        return redirect()->route('seller.wallet.index')
            ->with('success', 'Permintaan penarikan berhasil dikirim.');
    }
}