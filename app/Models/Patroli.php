<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patroli extends Model
{
    use HasFactory;

    protected $table = 'patroli';

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'tanggal_patroli',
        'waktu_patroli',
        'status',
        'keterangan',
        'foto',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     //Relasi ke tabel lokasi patroli
     public function lokasiPatroli()
     {
         return $this->belongsTo(LokasiPatroli::class, 'lokasi_id');
     }
}

