<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pergantian_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_patroli')->onDelete('cascade');
            $table->foreignId('petugas_lama_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('petugas_baru_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_pergantian');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pergantian_petugas');
    }
};
