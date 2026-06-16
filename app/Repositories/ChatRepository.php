<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\RoomChat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ChatRepository
{
    /**
     * Ambil semua room chat milik user.
     */
    public function findRoomsByUser(string $userId): Collection
    {
        return RoomChat::with(['user1', 'user2'])
            ->where('idUser1', $userId)
            ->orWhere('idUser2', $userId)
            ->orderBy('updateAt', 'desc')
            ->get();
    }

    /**
     * Cari room chat antara dua user.
     */
    public function findRoom(string $userId1, string $userId2): ?RoomChat
    {
        return RoomChat::where(function ($q) use ($userId1, $userId2) {
            $q->where('idUser1', $userId1)->where('idUser2', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('idUser1', $userId2)->where('idUser2', $userId1);
        })->first();
    }

    /**
     * Buat room chat baru.
     */
    public function createRoom(string $userId1, string $userId2): RoomChat
    {
        return RoomChat::create([
            'idUser1' => $userId1,
            'idUser2' => $userId2,
        ]);
    }

    /**
     * Ambil semua pesan dalam room.
     */
    public function findMessages(string $roomId): LengthAwarePaginator
    {
        return Chat::where('idRoom', $roomId)
            ->orderBy('createAt', 'desc')
            ->paginate(30);
    }

    /**
     * Kirim pesan baru.
     */
    public function sendMessage(string $roomId, string $senderId, string $message): Chat
    {
        $chat = Chat::create([
            'idRoom'      => $roomId,
            'senderId'    => $senderId,
            'messageText' => $message,
            'isRead'      => false,
        ]);

        // Update lastMessage di room
        RoomChat::where('idRoom', $roomId)
            ->update(['lastMessage' => $message]);

        return $chat;
    }

    /**
     * Tandai pesan sebagai sudah dibaca.
     */
    public function markAsRead(string $roomId, string $userId): bool
    {
        return Chat::where('idRoom', $roomId)
            ->where('senderId', '!=', $userId)
            ->where('isRead', false)
            ->update(['isRead' => true]);
    }
}