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
                'unit_id' => 5, 
                'nama_lokasi' => 'Halaman Depan',
                'latitude' => -7.6742850,
                'longitude' => 109.0623690,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 5, 
                'nama_lokasi' => 'Ruang Lobby',
                'latitude' => -7.6742020,
                'longitude' => 109.0624080,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 3,
                'nama_lokasi' => 'Ruang Kepala Cabang',
                'latitude' => -7.6741800,
                'longitude' => 109.0623500,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 3,
                'nama_lokasi' => 'Ruang Kerja',
                'latitude' => -7.6741600,
                'longitude' => 109.0623300,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 3,
                'nama_lokasi' => 'Ruang Kabid Dukbis',
                'latitude' => -7.6741400,
                'longitude' => 109.0623100,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 3,
                'nama_lokasi' => 'Ruang Dukbis',
                'latitude' => -7.6741200,
                'longitude' => 109.0622900,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 3,
                'nama_lokasi' => 'Ruang Kabid Inpeksi dan Pengujian',
                'latitude' => -7.6741000,
                'longitude' => 109.0622700,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 5, 
                'nama_lokasi' => 'Ruang Rapat',
                'latitude' => -7.6740800,
                'longitude' => 109.0622500,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 4,
                'nama_lokasi' => 'Ruang Administrasi Lab',
                'latitude' => -7.6740600,
                'longitude' => 109.0622300,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 2,
                'nama_lokasi' => 'Laboratorium Panas',
                'latitude' => -7.6740400,
                'longitude' => 109.0622100,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 2,
                'nama_lokasi' => 'Laboratorium Instrumen 1',
                'latitude' => -7.6740200,
                'longitude' => 109.0621900,
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
            [
                'unit_id' => 5,
                'nama_lokasi' => 'Halaman Belakang',
                'latitude' => -7.6739000,
                'longitude' => 109.0621800,
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
                'unit_id' => 5,
                'nama_lokasi' => 'Ruang Peralatan',
                'latitude' => -7.6738800,
                'longitude' => 109.0621600,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 2,
                'nama_lokasi' => 'Laboratorium Instrumen 2',
                'latitude' => -7.6738600,
                'longitude' => 109.0621400,
                'qr_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
