<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idChat' => Str::uuid()->toString(),
            // idRoom dan senderId disuntikkan via Seeder
            'messageText' => fake()->sentence(),
            'isRead' => fake()->boolean(70), // Asumsi 70% pesan sudah dibaca
        ];
    }
}