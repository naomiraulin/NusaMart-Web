<?php

namespace Database\Seeders;

use App\Models\RoomChat;
use App\Models\Chat;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = RoomChat::all();

        if ($rooms->count() > 0) {
            foreach ($rooms as $room) {
                // Buat 3 sampai 8 pesan percakapan di dalam room ini
                $messagesCount = rand(3, 8);
                $lastMessageText = '';

                for ($i = 0; $i < $messagesCount; $i++) {
                    // Tentukan pengirim secara acak (bisa pembeli/idUser1 atau penjual/idUser2)
                    $senderId = fake()->randomElement([$room->idUser1, $room->idUser2]);
                    
                    $chat = Chat::factory()->create([
                        'idRoom' => $room->idRoom,
                        'senderId' => $senderId,
                    ]);

                    // Simpan teks pesan terakhir yang dibuat di dalam loop ini
                    $lastMessageText = $chat->messageText;
                }

                // Setelah perulangan selesai, update lastMessage di tabel RoomChat
                $room->update([
                    'lastMessage' => $lastMessageText
                ]);
            }
        }
    }
}