<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomChat;
use App\Models\Chat;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/chat/rooms → semua room chat user
    public function rooms(Request $request)
    {
        $userId = $request->user()->idUser;

        $rooms = RoomChat::where('idUser1', $userId)
            ->orWhere('idUser2', $userId)
            ->orderByDesc('updateAt')
            ->get();

        return response()->json($rooms);
    }

    // GET /api/chat/rooms/{id} → detail room
    public function roomDetail(string $id)
    {
        $room = RoomChat::where('idRoom', $id)->firstOrFail();
        return response()->json($room);
    }

    // POST /api/chat/rooms → buat atau ambil room yang sudah ada
    public function getOrCreateRoom(Request $request)
    {
        $request->validate([
            'idUser2' => 'required|string',
        ]);

        $userId1 = $request->user()->idUser;
        $userId2 = $request->idUser2;

        // Cek apakah room sudah ada
        $existing = RoomChat::where(function ($q) use ($userId1, $userId2) {
            $q->where('idUser1', $userId1)->where('idUser2', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('idUser1', $userId2)->where('idUser2', $userId1);
        })->first();

        if ($existing) {
            return response()->json($existing);
        }

        $room = RoomChat::create([
            'idRoom'      => $this->idGenerator->generate('RCH', RoomChat::class, 'idRoom'),
            'idUser1'     => $userId1,
            'idUser2'     => $userId2,
            'lastMessage' => null,
            'createAt'    => now(),
            'updateAt'    => now(),
        ]);

        return response()->json($room, 201);
    }

    // GET /api/chat/rooms/{id}/messages → semua pesan di room
    public function messages(string $id)
    {
        $chats = Chat::where('idRoom', $id)
            ->orderBy('createAt')
            ->get();

        return response()->json($chats);
    }

    // POST /api/chat/rooms/{id}/messages → kirim pesan
    public function sendMessage(Request $request, string $id)
    {
        $request->validate([
            'messageText' => 'required|string',
        ]);

        $room = RoomChat::where('idRoom', $id)->firstOrFail();

        $chat = Chat::create([
            'idChat'      => $this->idGenerator->generate('CHT', Chat::class, 'idChat'),
            'idRoom'      => $id,
            'senderId'    => $request->user()->idUser,
            'messageText' => $request->messageText,
            'isRead'      => false,
            'createAt'    => now(),
        ]);

        // Update lastMessage di room
        $room->update([
            'lastMessage' => $request->messageText,
            'updateAt'    => now(),
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim',
            'chat'    => $chat,
        ], 201);
    }

    // PUT /api/chat/rooms/{id}/read → tandai pesan sebagai sudah dibaca
    public function markAsRead(Request $request, string $id)
    {
        $userId = $request->user()->idUser;

        Chat::where('idRoom', $id)
            ->where('senderId', '!=', $userId)
            ->where('isRead', false)
            ->update(['isRead' => true]);

        return response()->json(['message' => 'Pesan berhasil ditandai sudah dibaca']);
    }
}