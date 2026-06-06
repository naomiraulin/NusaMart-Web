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
            foreach ($buyers as $buyer) {
                // Simulasikan tidak semua pembeli pernah nge-chat (misal hanya 60%)
                if (rand(1, 100) <= 60) {
                    // Ambil 1-2 penjual acak untuk diajak chat oleh pembeli ini
                    $randomSellers = $sellers->random(rand(1, 2));

                    foreach ($randomSellers as $seller) {
                        RoomChat::factory()->create([
                            'idUser1' => $buyer->idUser,
                            'idUser2' => $seller->idUser,
                        ]);
                    }
                }
            }
        }
    }
}