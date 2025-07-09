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
        Schema::create('patroli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi_patroli')->onDelete('cascade'); 
            $table->foreignId('unit_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->foreignId('kejadian_id')->nullable()->constrained('kategori_kejadian')->onDelete('cascade');
            $table->date('tanggal_patroli');
            $table->time('waktu_patroli');
            $table->enum('status', ['aman', 'darurat']);
            $table->text('keterangan');
            $table->string('foto'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patroli');
    }
};
