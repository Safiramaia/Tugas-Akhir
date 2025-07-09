<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriKejadianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_kejadian')->insert([
            [
                'nama_kategori' => 'Listrik Padam',
                'kirim_notifikasi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kebakaran',
                'kirim_notifikasi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kebocoran Gas',
                'kirim_notifikasi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Alat Rusak',
                'kirim_notifikasi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Konsleting Listrik',
                'kirim_notifikasi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
