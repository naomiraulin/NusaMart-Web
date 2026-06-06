<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('idReview')->primary();
            
            // Foreign Keys
            $table->string('idOrderItem');
            $table->string('idUser');
            
            // Rating dan Ulasan
            $table->double('rating');
            $table->text('comment')->nullable(); // Pakai text agar bisa menampung ulasan panjang
            $table->boolean('isHidden')->default(false);

            // Definisi Relasi
            // 1 OrderItem idealnya hanya bisa di-review 1 kali, kita biarkan di level aplikasi logic-nya
            $table->foreign('idOrderItem')->references('idOrderItem')->on('order_items')->onDelete('cascade');
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};