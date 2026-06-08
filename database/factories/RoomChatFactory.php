<?php

namespace Database\Factories;

use App\Models\RoomChat;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idRoom'      => app(IdGeneratorService::class)->generate('RCH', RoomChat::class, 'idRoom'),
            'lastMessage' => fake()->randomElement([
                'Halo min, apakah produk ini masih ada?',
                'Iya kak, barang ready. Silakan diorder ya.',
                'Terima kasih, paket sudah saya terima.',
                'Bisa dikirim hari ini pakai Instan?',
                'Maaf kak, varian warna merah sedang kosong.'
            ]),
            'createAt' => now(),
            'updateAt' => now(),
        ];
    }
}