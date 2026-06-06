<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->string('idMethod')->primary();
            
            // Kolom baru untuk grouping di UI
            $table->string('category'); 
            
            $table->string('methodName');
            
            // Kolom baru untuk teks kecil di bawah nama metode
            $table->string('description')->nullable(); 
            
            $table->enum('provider', ['MIDTRANS', 'MANUAL', 'COD']);
            $table->boolean('isActive')->default(true);

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};