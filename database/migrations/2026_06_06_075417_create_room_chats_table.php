<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_chats', function (Blueprint $table) {
            $table->string('idRoom')->primary();
            
            // Foreign Keys (Keduanya mengarah ke tabel users)
            $table->string('idUser1'); // Pembeli (Buyer)
            $table->string('idUser2'); // Penjual (Seller)
            
            // Menyimpan cuplikan pesan terakhir untuk tampilan daftar chat
            $table->string('lastMessage')->nullable();

            // Definisi Relasi
            $table->foreign('idUser1')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idUser2')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_chats');
    }
};