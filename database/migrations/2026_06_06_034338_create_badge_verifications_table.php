<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badge_verifications', function (Blueprint $table) {
            $table->string('idBadge')->primary();
            
            // Foreign Key ke tabel stores
            $table->string('idStore');
            
            $table->string('badgeType')->default('VERIFIED LOCAL');
            $table->dateTime('reviewDate')->nullable();
            $table->dateTime('requestDate')->nullable();
            $table->dateTime('endDate')->nullable(); // Tanggal kedaluwarsa badge
            
            // Enum untuk status sesuai di gambar
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'EXPIRED'])->default('PENDING');
            
            $table->string('notes')->nullable();

            // Relasi ke tabel stores
            $table->foreign('idStore')->references('idStore')->on('stores')->onDelete('cascade');

            // Custom Timestamps bawaan sistemmu
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badge_verifications');
    }
};