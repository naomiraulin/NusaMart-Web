<?php

namespace Database\Seeders;

use App\Models\RoomChat;
use App\Models\Chat;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = RoomChat::all();

        if ($rooms->count() > 0) {
            $counter = 1;

            // Koleksi kalimat realistis untuk simulasi chat marketplace
            $dummyMessages = [
                "Halo kak, apakah produk ini masih ready?",
                "Masih kak, silakan langsung diorder ya.",
                "Bisa dikirim hari ini pakai instan?",
                "Bisa banget kak, maksimal order jam 3 sore ya.",
                "Oke, saya checkout sekarang.",
                "Terima kasih kak, pesanan segera kami proses.",
                "Ukurannya L ready warna apa saja?",
                "Warna hitam dan navy kak yang ready.",
                "Tolong dipacking yang aman ya, buat kado soalnya.",
                "Baik kak, kami pastikan packing aman pakai bubble wrap tebal."
            ];

            foreach ($rooms as $room) {
                // Buat 3 sampai 8 pesan percakapan di dalam room ini
                $messagesCount = rand(3, 8);
                $lastMessageText = '';
                
                // Set waktu awal percakapan (mundur 1-3 hari yang lalu)
                $timeSimulator = Carbon::now()->subDays(rand(1, 3));

                for ($i = 0; $i < $messagesCount; $i++) {
                    // Waktu bertambah 1-15 menit untuk tiap pesan agar urutannya logis
                    $timeSimulator->addMinutes(rand(1, 15));

                    // Tentukan pengirim secara acak
                    $senders = [$room->idUser1, $room->idUser2];
                    $senderId = $senders[array_rand($senders)];
                    
                    // Ambil pesan acak
                    $messageText = $dummyMessages[array_rand($dummyMessages)];
                    
                    Chat::create([
                        // Generate ID Chat (CHT-000001)
                        'idChat'      => 'CHT-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                        'idRoom'      => $room->idRoom,
                        'senderId'    => $senderId,
                        'messageText' => $messageText,
                        'isRead'      => (bool) rand(0, 1),
                        'createAt'    => $timeSimulator->copy(),
                    ]);

                    // Simpan teks pesan terakhir
                    $lastMessageText = $messageText;
                }

                // Setelah perulangan selesai, update lastMessage di tabel RoomChat
                $room->update([
                    'lastMessage' => $lastMessageText
                ]);
            }
        }
    }
}