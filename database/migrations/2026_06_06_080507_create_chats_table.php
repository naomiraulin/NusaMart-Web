<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->string('idChat')->primary();
            
            // Foreign Keys
            $table->string('idRoom');
            $table->string('senderId'); // Mengarah ke tabel users
            
            // Menggunakan text agar bisa menampung pesan yang sangat panjang
            $table->text('messageText');
            $table->boolean('isRead')->default(false);

            // Definisi Relasi
            $table->foreign('idRoom')->references('idRoom')->on('room_chats')->onDelete('cascade');
            $table->foreign('senderId')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};