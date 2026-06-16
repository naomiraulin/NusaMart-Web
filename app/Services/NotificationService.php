<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Services\IdGeneratorService;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private IdGeneratorService     $idGenerator,
    ) {}

    /**
     * Ambil semua notifikasi user.
     */
    public function getByUser(string $userId): LengthAwarePaginator
    {
        return $this->notificationRepository->findByUser($userId);
    }

    /**
     * Hitung notifikasi yang belum dibaca.
     */
    public function countUnread(string $userId): int
    {
        return $this->notificationRepository->countUnread($userId);
    }

    /**
     * Kirim notifikasi ke user.
     */
    public function send(
        string  $userId,
        string  $title,
        string  $body,
        string  $type = 'SISTEM',
        ?string $referenceId = null,
        ?string $referenceType = null,
    ): Notification {
        return $this->notificationRepository->create([
            'idNotif'       => $this->idGenerator->generate('NTF', Notification::class, 'idNotif'),
            'idUser'        => $userId,
            'title'         => $title,
            'body'          => $body,
            'type'          => $type,
            'isRead'        => false,
            'referenceId'   => $referenceId,
            'referenceType' => $referenceType,
        ]);
    }

    /**
     * Shortcut — kirim notifikasi terkait order.
     */
    public function sendOrderNotif(string $userId, string $orderId, string $status): Notification
    {
        $messages = [
            'PENDING'   => 'Pesananmu berhasil dibuat. Menunggu konfirmasi seller.',
            'PROCESSED' => 'Seller sedang memproses pesananmu.',
            'SHIPPED'   => 'Pesananmu sedang dalam pengiriman.',
            'DELIVERED' => 'Pesananmu telah sampai. Jangan lupa beri ulasan!',
            'CANCELLED' => 'Pesananmu telah dibatalkan.',
        ];

        return $this->send(
            userId       : $userId,
            title        : 'Update Pesanan',
            body         : $messages[$status] ?? "Status pesanan diperbarui: {$status}",
            type         : 'ORDER',
            referenceId  : $orderId,
            referenceType: 'ORDER',
        );
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $notifId): Notification
    {
        return $this->notificationRepository->markAsRead($notifId);
    }

    /**
     * Tandai semua notifikasi user sebagai dibaca.
     */
    public function markAllAsRead(string $userId): bool
    {
        return $this->notificationRepository->markAllAsRead($userId);
    }
}