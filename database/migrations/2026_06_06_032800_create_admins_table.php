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
        Schema::create('admins', function (Blueprint $table) {
            // idAdmin bertindak sebagai Primary Key (String)
            $table->string('idAdmin')->primary();
            
            $table->string('division');
            $table->string('accessLevel');

            // Relasi Foreign Key ke kolom idUser di tabel users
            $table->foreign('idAdmin')->references('idUser')->on('users')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};