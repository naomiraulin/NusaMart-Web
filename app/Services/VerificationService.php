<?php

namespace App\Services;

use App\Models\BadgeVerification;
use App\Repositories\VerificationRepository;
use App\Services\NotificationService;

class VerificationService
{
    public function __construct(
        private VerificationRepository $verificationRepository,
        private NotificationService $notificationService
    ) {}

    public function approve(string $id, ?string $notes = null): BadgeVerification
    {
        $verification = $this->verificationRepository->findById($id);

        $this->verificationRepository->update($verification, [
            'status'     => 'APPROVED',
            'reviewDate' => now(),
            'endDate'    => now()->addYear(),
            'notes'      => $notes,
        ]);

        // Kirim Notifikasi
        $sellerId = $verification->store->seller->idUser ?? $verification->store->seller->idSeller; 
        $this->notificationService->send(
            userId: $sellerId,
            title: 'Verifikasi Toko Disetujui',
            body: 'Selamat! Toko kamu telah berhasil diverifikasi.',
            type: 'SISTEM'
        );

        return $verification;
    }

    public function reject(string $id, string $notes): BadgeVerification
    {
        $verification = $this->verificationRepository->findById($id);

        $this->verificationRepository->update($verification, [
            'status'     => 'REJECTED',
            'reviewDate' => now(),
            'notes'      => $notes,
        ]);

        // Kirim Notifikasi
        $sellerId = $verification->store->seller->idUser ?? $verification->store->seller->idSeller;
        $this->notificationService->send(
            userId: $sellerId,
            title: 'Verifikasi Toko Ditolak',
            body: "Pengajuan verifikasi toko kamu ditolak. Alasan: {$notes}",
            type: 'SISTEM'
        );

        return $verification;
    }
}