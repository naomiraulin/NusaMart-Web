<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RoomChat;
use Illuminate\Database\Seeder;

class RoomChatSeeder extends Seeder
{
    public function run(): void
    {
        // Pisahkan user berdasarkan role
        $buyers = User::where('role', 'BUYER')->get();
        $sellers = User::where('role', 'SELLER')->get();

        if ($buyers->count() > 0 && $sellers->count() > 0) {
            $counter = 1;

            foreach ($buyers as $buyer) {
                // Simulasikan tidak semua pembeli pernah nge-chat (misal hanya 60%)
                if (rand(1, 100) <= 60) {
                    // Ambil 1-2 penjual acak untuk diajak chat oleh pembeli ini
                    $randomSellers = $sellers->random(rand(1, min(2, $sellers->count())));

                    foreach ($randomSellers as $seller) {
                        RoomChat::create([
                            // Generate ID Room (ROM-000001, ROM-000002)
                            'idRoom'      => 'ROM-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                            'idUser1'     => $buyer->idUser,
                            'idUser2'     => $seller->idUser,
                            'lastMessage' => null, // Dikosongkan dulu, nanti diisi oleh ChatSeeder
                        ]);
                    }
                }
            }
        }
    }
}