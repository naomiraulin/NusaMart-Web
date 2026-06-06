<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->string('idCart')->primary();
            
            // Foreign Key ke tabel users
            $table->string('idUser')->unique(); // Unique memastikan 1 user hanya punya 1 cart

            // Relasi dengan cascade delete
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};