<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $role = fake()->randomElement(['BUYER', 'SELLER']);

        return [
            'idUser'   => app(UserService::class)->generateUserId($role),
            'username' => fake()->userName(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone'    => fake()->phoneNumber(),
            'role'     => $role,
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ];
    }
}