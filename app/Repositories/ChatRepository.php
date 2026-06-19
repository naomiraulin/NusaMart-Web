<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\RoomChat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\IdGeneratorService;


class ChatRepository
{
    
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}
    
    /**
     * Ambil semua room chat milik user.
     */
    public function findRoomsByUser(string $userId): Collection
    {
        return RoomChat::with(['user1', 'user2'])
            ->where(function ($query) use ($userId) {
                $query->where('idUser1', $userId)
                      ->orWhere('idUser2', $userId);
            })
            ->orderBy('updateAt', 'desc') // Pastikan nama kolom di DB benar 'updateAt'
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
        // MENGGUNAKAN INSTANSIASI MANUAL:
        // Ini akan mengabaikan batasan $fillable yang ada di Model
        $room = new RoomChat();
        $room->idRoom  = $this->idGenerator->generate('ROM', RoomChat::class, 'idRoom');
        $room->idUser1 = $userId1;
        $room->idUser2 = $userId2;
        
        // Simpan ke database
        $room->save();

        return $room;
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
        // Gunakan instansiasi manual juga untuk tabel Chat agar aman dari error yang sama
        $chat = new Chat();
        $chat->idChat      = $this->idGenerator->generate('CHT', Chat::class, 'idChat');
        $chat->idRoom      = $roomId;
        $chat->senderId    = $senderId;
        $chat->messageText = $message;
        $chat->isRead      = false;
        
        $chat->save();

        // Update lastMessage dan updateAt di room agar naik ke atas
        RoomChat::where('idRoom', $roomId)
            ->update([
                'lastMessage' => $message,
                'updateAt'    => now()
            ]);

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