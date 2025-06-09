<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPatroli extends Model
{
    protected $table = 'jadwal_patroli';

    protected $fillable = [
        'user_id',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
