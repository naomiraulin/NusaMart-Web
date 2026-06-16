<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\RoomChat;
use App\Repositories\ChatRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ChatService
{
    public function __construct(
        private ChatRepository $chatRepository,
    ) {}

    /**
     * Ambil semua room chat milik user.
     */
    public function getRooms(string $userId): Collection
    {
        return $this->chatRepository->findRoomsByUser($userId);
    }

    /**
     * Ambil atau buat room chat antara buyer & seller.
     */
    public function getOrCreateRoom(string $buyerId, string $sellerId): RoomChat
    {
        $room = $this->chatRepository->findRoom($buyerId, $sellerId);

        if (!$room) {
            $room = $this->chatRepository->createRoom($buyerId, $sellerId);
        }

        return $room;
    }

    /**
     * Ambil semua pesan dalam room.
     */
    public function getMessages(string $roomId, string $userId): LengthAwarePaginator
    {
        // Tandai semua pesan sebagai dibaca saat dibuka
        $this->chatRepository->markAsRead($roomId, $userId);

        return $this->chatRepository->findMessages($roomId);
    }

    /**
     * Kirim pesan.
     */
    public function sendMessage(string $roomId, string $senderId, string $message): Chat
    {
        return $this->chatRepository->sendMessage($roomId, $senderId, $message);
    }
}