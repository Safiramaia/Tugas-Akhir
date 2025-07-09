<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKejadian extends Model
{
    use HasFactory;

    protected $table = 'kategori_kejadian';

    protected $fillable = [
        'nama_kategori',
        'kirim_notifikasi',
    ];

    // Relasi ke tabel patroli 
    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'kejadian_id');
    }
}
