<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    /**
     * Ambil semua notifikasi milik user.
     */
    public function findByUser(string $userId): LengthAwarePaginator
    {
        return Notification::where('idUser', $userId)
            ->orderBy('createAt', 'desc')
            ->paginate(20);
    }

    /**
     * Hitung notifikasi yang belum dibaca.
     */
    public function countUnread(string $userId): int
    {
        return Notification::where('idUser', $userId)
            ->where('isRead', false)
            ->count();
    }

    /**
     * Buat notifikasi baru.
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(string $id): Notification
    {
        $notif = Notification::where('idNotif', $id)->firstOrFail();
        $notif->update(['isRead' => true]);

        return $notif->fresh();
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllAsRead(string $userId): bool
    {
        return Notification::where('idUser', $userId)
            ->where('isRead', false)
            ->update(['isRead' => true]);
    }
}