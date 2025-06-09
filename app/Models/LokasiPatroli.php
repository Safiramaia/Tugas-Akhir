<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPatroli extends Model
{
    use HasFactory;

    protected $table = 'lokasi_patroli';

    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'qr_code'
    ];

    public function patroli()
{
    return $this->hasMany(Patroli::class, 'lokasi_id'); 
}
}
