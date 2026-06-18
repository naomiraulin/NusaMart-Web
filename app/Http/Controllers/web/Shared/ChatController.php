<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\RoomChat;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService,
    ) {}

    /**
     * Halaman daftar semua room chat user.
     */
    public function index(): View
    {
        /** @var string $userId */
        $userId = Auth::id();
        $rooms = $this->chatService->getRooms($userId);

        return view('shared.chat.index', compact('rooms'));
    }

    /**
     * Halaman room chat dengan user tertentu.
     * Buyer buka chat ke seller, atau seller balas ke buyer.
     */
    public function show(string $roomId): View
    {
        /** @var string $userId */
        $userId = Auth::id();
        $messages = $this->chatService->getMessages($roomId, $userId);

        // ChatController.php - show()
        $room = RoomChat::find($roomId); // atau lewat service
        return view('shared.chat.show', compact('roomId', 'messages', 'room'));

    }

    /**
     * Buyer mulai chat ke seller dari halaman toko/produk.
     */
    public function openWithSeller(string $sellerId): RedirectResponse
    {
        /** @var string $userId */
        $userId = Auth::id();
        $room = $this->chatService->getOrCreateRoom($userId, $sellerId);

        return redirect()->route('chat.show', $room->idRoom);
    }

    /**
     * Kirim pesan baru.
     */
    public function send(Request $request, string $roomId): RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        /** @var string $userId */
        $userId = Auth::id();
        $this->chatService->sendMessage($roomId, $userId, $request->input('message'));

        return back();
    }
}