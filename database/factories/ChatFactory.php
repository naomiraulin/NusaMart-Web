<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idChat'      => app(IdGeneratorService::class)->generate('CHT', Chat::class, 'idChat'),
            'messageText' => fake()->sentence(),
            'isRead'      => fake()->boolean(70),
            'createAt'    => now(),
        ];
    }
}