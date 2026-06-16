<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/notifications → semua notifikasi user
    public function index(Request $request)
    {
        $notifications = Notification::where('idUser', $request->user()->idUser)
            ->orderByDesc('createAt')
            ->get();

        return response()->json($notifications);
    }

    // GET /api/notifications/{id} → detail notifikasi
    public function show(Request $request, string $id)
    {
        $notification = Notification::where('idNotif', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        return response()->json($notification);
    }

    // PUT /api/notifications/{id}/read → tandai satu notifikasi sudah dibaca
    public function markAsRead(Request $request, string $id)
    {
        $notification = Notification::where('idNotif', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        if (!$notification->isRead) {
            $notification->update(['isRead' => true]);
        }

        return response()->json([
            'message'      => 'Notifikasi berhasil ditandai sudah dibaca',
            'notification' => $notification,
        ]);
    }

    // PUT /api/notifications/read-all → tandai semua notifikasi sudah dibaca
    public function markAllAsRead(Request $request)
    {
        Notification::where('idUser', $request->user()->idUser)
            ->where('isRead', false)
            ->update(['isRead' => true]);

        return response()->json([
            'message' => 'Semua notifikasi berhasil ditandai sudah dibaca'
        ]);
    }
}