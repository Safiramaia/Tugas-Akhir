<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPatroli extends Model
{
    use HasFactory;

    protected $table = 'lokasi_patroli';

    protected $fillable = [
        'unit_id',
        'nama_lokasi',
        'latitude',
        'longitude',
        'qr_code',
    ];

    //Relasi dengan tabel unit kerja
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    //Relasi dengan tabel patroli
    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'lokasi_id'); 
    }

}
