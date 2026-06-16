<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findById(string $id): ?User
    {
        return User::where('idUser', $id)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(string $id, array $data): User
    {
        $user = User::where('idUser', $id)->firstOrFail();
        $user->update($data);

        return $user->fresh();
    }
}