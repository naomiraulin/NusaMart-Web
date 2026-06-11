<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dari database
        // Opsional: Jika kamu punya pemisahan role, bisa gunakan User::where('role', 'buyer')->get();
        $users = User::where('role', 'buyer')->get(); 
        
        $counter = 1;

        foreach ($users as $user) {
            Cart::create([
                // Generate ID Cart berurutan otomatis (CRT-000001, CRT-000002, dst.)
                'idCart' => 'CRT-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                
                // Pasangkan dengan ID User yang benar-benar ada di database
                'idUser' => $user->idUser, 
            ]);
        }
    }
}