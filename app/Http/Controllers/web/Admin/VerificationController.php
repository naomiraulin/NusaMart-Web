<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadgeVerification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    /**
     * Daftar semua pengajuan verifikasi toko.
     */
    public function index(Request $request): View
    {
        $query = BadgeVerification::with(['store.seller.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $verifications = $query->latest('requestDate')->paginate(15);

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Detail pengajuan verifikasi.
     */
    public function show(string $id): View
    {
        $verification = BadgeVerification::with(['store.seller.user'])
            ->where('idBadge', $id)
            ->firstOrFail();

        return view('admin.verifications.show', compact('verification'));
    }

    /**
     * Setujui verifikasi toko.
     */
    public function approve(Request $request, string $id): RedirectResponse
    {
        $verification = BadgeVerification::where('idBadge', $id)->firstOrFail();

        $verification->update([
            'status'     => 'APPROVED',
            'reviewDate' => now(),
            'endDate'    => now()->addYear(),
            'notes'      => $request->input('notes'),
        ]);

        // Notifikasi ke seller
        $sellerId = $verification->store->seller->idSeller;
        $this->notificationService->send(
            userId : $sellerId,
            title  : 'Verifikasi Toko Disetujui',
            body   : 'Selamat! Toko kamu telah berhasil diverifikasi.',
            type   : 'SISTEM',
        );

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Verifikasi toko berhasil disetujui.');
    }

    /**
     * Tolak verifikasi toko.
     */
    public function reject(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ]);

        $verification = BadgeVerification::where('idBadge', $id)->firstOrFail();

        $verification->update([
            'status'     => 'REJECTED',
            'reviewDate' => now(),
            'notes'      => $request->input('notes'),
        ]);

        // Notifikasi ke seller
        $sellerId = $verification->store->seller->idSeller;
        $this->notificationService->send(
            userId : $sellerId,
            title  : 'Verifikasi Toko Ditolak',
            body   : "Pengajuan verifikasi toko kamu ditolak. Alasan: {$request->input('notes')}",
            type   : 'SISTEM',
        );

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Verifikasi toko berhasil ditolak.');
    }
}