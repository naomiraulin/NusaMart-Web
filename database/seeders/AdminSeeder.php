<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang memiliki role ADMIN dari database
        $adminUsers = User::where('role', 'ADMIN')->get();

        foreach ($adminUsers as $user) {
            // Buat data admin dengan menyamakan idAdmin dengan idUser
            Admin::factory()->create([
                'idAdmin' => $user->idUser,
            ]);
        }
    }
}