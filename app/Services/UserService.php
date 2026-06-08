<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    public function generateUserId(string $role): string
    {
        $prefix = match($role) {
            'BUYER'  => 'BYR',
            'SELLER' => 'SLR',
            'ADMIN'  => 'ADM',
            default  => 'USR',
        };

        return $this->idGenerator->generate($prefix, User::class, 'idUser');
    }

    public function createUser(array $data): User
    {
        return User::create([
            'idUser'   => $this->generateUserId($data['role']),
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'phone'    => $data['phone'] ?? null,
            'role'     => $data['role'] ?? 'BUYER',
            'imageURL' => $data['imageURL'] ?? null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);
    }
}