<?php

namespace Database\Factories;

use App\Models\RoomChat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoomChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idRoom' => Str::uuid()->toString(),
            // idUser1 dan idUser2 akan disuntikkan dari Seeder
            'lastMessage' => fake()->randomElement([
                'Halo min, apakah produk ini masih ada?',
                'Iya kak, barang ready. Silakan diorder ya.',
                'Terima kasih, paket sudah saya terima.',
                'Bisa dikirim hari ini pakai Instan?',
                'Maaf kak, varian warna merah sedang kosong.'
            ]),
        ];
    }
}