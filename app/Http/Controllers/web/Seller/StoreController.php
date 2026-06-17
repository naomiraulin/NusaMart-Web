<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Services\StoreService;
use App\Models\BadgeVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct(
        private StoreService $storeService,
    ) {}

    /**
     * Halaman profil toko seller.
     */
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        return view('seller.store.show', compact('store'));
    }

    /**
     * Form edit profil toko.
     */
    public function edit(): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        return view('seller.store.edit', compact('store'));
    }

    /**
     * Update profil toko.
     */
    public function update(UpdateStoreRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $this->storeService->update(
            $store->idStore,
            $request->validated(),
            $request->file('logo'),
        );

        return redirect()->route('seller.store.show')
            ->with('success', 'Profil toko berhasil diperbarui.');
    }

    /**
     * Ajukan verifikasi toko.
     */
    public function requestVerification(): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        \App\Models\BadgeVerification::create([
            'idBadge'     => app(\App\Services\IdGeneratorService::class)->generate('BDG', BadgeVerification::class, column: 'idBadge'),
            'idStore'     => $store->idStore,
            'badgeType'   => 'VERIFIED',
            'requestDate' => now(),
            'status'      => 'PENDING',
        ]);

        return back()->with('success', 'Permohonan verifikasi berhasil dikirim.');
    }
}