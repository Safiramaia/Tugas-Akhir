<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiPatroliSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lokasi_patroli')->insert([
            [
                'unit_id' => 3, 
                'nama_lokasi' => 'Halaman Depan',
                'latitude' => -7.6742850,	
                'longitude' => 109.0623690,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 4, 
                'nama_lokasi' => 'Ruang Lobby',
                'latitude' => -7.6742020,	
                'longitude' => 109.0624080,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 1, 
                'nama_lokasi' => 'Ruang Server',
                'latitude' => -7.6742000,	
                'longitude' => 109.0622590,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 2, 
                'nama_lokasi' => 'Laboratorium Kalori',
                'latitude' => -7.6739350,	
                'longitude' => 109.0622620,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
