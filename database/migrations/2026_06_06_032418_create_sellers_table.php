<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            // idSeller bertindak sebagai Primary Key (String)
            $table->string('idSeller')->primary();
            
            $table->string('nik')->unique();
            $table->string('bankName');
            $table->string('accountNumber');

            // Relasi Foreign Key ke kolom idUser di tabel users
            $table->foreign('idSeller')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps agar konsisten dengan tabel users
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};