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
            $table->string('idSeller')->primary();
            $table->string('nik')->unique();
            $table->string('bankName');
            $table->string('accountNumber');
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();

            // Foreign key di AKHIR setelah semua kolom dideklarasikan
            $table->foreign('idSeller')->references('idUser')->on('users')->onDelete('cascade');
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