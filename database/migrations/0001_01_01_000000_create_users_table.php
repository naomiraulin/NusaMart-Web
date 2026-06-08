<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('idUser')->primary();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('role', ['BUYER', 'SELLER', 'ADMIN'])->default('BUYER');
            $table->string('imageURL')->nullable();
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};