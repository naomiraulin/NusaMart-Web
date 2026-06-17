<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    /**
     * Halaman semua notifikasi.
     */
    public function index(): View
    {
        /** @var string $userId */
        $userId = Auth::id();
        $notifications = $this->notificationService->getByUser($userId);
        $unreadCount   = $this->notificationService->countUnread($userId);

        return view('shared.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $this->notificationService->markAsRead($id);

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        /** @var string $userId */
        $userId = Auth::id();
        $this->notificationService->markAllAsRead($userId);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Ambil jumlah notifikasi belum dibaca (untuk badge di navbar).
     * Dipanggil via AJAX.
     */
    public function unreadCount(): JsonResponse
    {
        /** @var string $userId */
        $userId = Auth::id();
        $count = $this->notificationService->countUnread($userId);

        return response()->json(['count' => $count]);
    }
}