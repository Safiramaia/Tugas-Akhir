<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit',
    ];

    //Relasi dengan tabel lokasi patroli
    public function lokasiPatroli()
    {
        return $this->hasMany(LokasiPatroli::class, 'unit_id');
    }

    //Relasi dengan tabel patroli
     public function patroli()
    {
        return $this->hasMany(Patroli::class, 'unit_id'); 
    }
}
