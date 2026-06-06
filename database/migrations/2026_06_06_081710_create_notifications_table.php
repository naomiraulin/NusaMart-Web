<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('idNotif')->primary();
            
            // Foreign Key ke tabel users
            $table->string('idUser');
            
            $table->string('title');
            $table->text('body');
            
            // Tipe Utama: ORDER atau SISTEM
            $table->string('type'); 
            
            $table->boolean('isRead')->default(false);
            
            // Polymorphic References untuk Deep Linking di Android
            $table->string('referenceId')->nullable();
            $table->string('referenceType')->nullable(); // ORDER / PAYMENT / SYSTEM

            // Definisi Relasi
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};