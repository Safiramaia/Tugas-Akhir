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
        'unit_id',
        'kejadian_id',
        'tanggal_patroli',
        'waktu_patroli',
        'status',
        'keterangan',
        'foto', 
    ];

    // Relasi ke user (petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke lokasi patroli
    public function lokasiPatroli()
    {
        return $this->belongsTo(LokasiPatroli::class, 'lokasi_id');
    }

    // Relasi ke unit kerja
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    // Relasi ke kategori kejadian (jika sudah ditambahkan kolom ini di tabel patroli)
    public function kategoriKejadian()
    {
        return $this->belongsTo(KategoriKejadian::class, 'kejadian_id');
    }
}
