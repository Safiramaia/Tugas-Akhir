<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PergantianPetugas extends Model
{
    use HasFactory;

    protected $table = 'pergantian_petugas';

    protected $fillable = [
        'jadwal_id',
        'petugas_lama_id',
        'petugas_baru_id',
        'waktu_pergantian',
        'alasan',
    ];

    // Relasi ke tabel jadwal patroli
    public function jadwal()
    {
        return $this->belongsTo(JadwalPatroli::class, 'jadwal_id');
    }

    // Relasi ke tabel user untuk petugas lama
    public function petugasLama()
    {
        return $this->belongsTo(User::class, 'petugas_lama_id');
    }

    // Relasi ke tabel user untuk petugas baru
    public function petugasBaru()
    {
        return $this->belongsTo(User::class, 'petugas_baru_id');
    }
}

