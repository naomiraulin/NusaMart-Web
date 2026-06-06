<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user (bisa dibatasi hanya role BUYER jika mau)
        $users = User::where('role', 'BUYER')->get();

        foreach ($users as $user) {
            Cart::factory()->create([
                'idUser' => $user->idUser,
            ]);
        }
    }
}