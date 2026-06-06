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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->string('idAddress')->primary();
            
            // Foreign Key
            $table->string('idUser');
            
            $table->string('label'); // Contoh: 'Rumah', 'Kantor'
            $table->string('receiver');
            $table->string('phone');
            $table->text('completeAddress'); // Pakai text karena alamat bisa panjang
            $table->string('city');
            $table->string('province');
            $table->string('postalCode');
            $table->boolean('isDefault')->default(false);

            // Mendefinisikan relasi ke tabel users
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};