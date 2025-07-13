<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'nomor_induk' => '01202504001',
                'no_telepon' => '085713333394',
                'role' => 'admin',
                'alamat' => 'Jalan Nangka, Cilacap',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Agung',
                'email' => 'agungsci@gmail.com',
                'nomor_induk' => '02202505002',
                'no_telepon' => '085634677811',
                'role' => 'petugas_security',
                'alamat' => 'Jalan Gatot Subroto, Cilacap Tengah',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ahmad',
                'email' => 'ahmadsci@gmail.com',
                'nomor_induk' => '02202505005',
                'no_telepon' => '085634672345',
                'role' => 'petugas_security',
                'alamat' => 'Jalan Badak, Cilacap Utara',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Badrun',
                'email' => 'badrunsci@gmail.com',
                'nomor_induk' => '02202505004',
                'no_telepon' => '085634678901',
                'role' => 'petugas_security',
                'alamat' => 'Jalan Kauman, Cilacap Selatan',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Irfan Hadi',
                'email' => 'irfansci@gmail.com',
                'nomor_induk' => '03202505003',
                'no_telepon' => '085713333390',
                'role' => 'kabid_dukbis',
                'alamat' => 'Jalan Merapi, Cilacap Tengah',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bagas',
                'email' => 'bagassci@gmail.com',
                'nomor_induk' => '04202506006',
                'no_telepon' => '085712345678',
                'role' => 'unit',
                'alamat' => 'Jalan Cemara, Cilacap',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bayu',
                'email' => 'bayusci@gmail.com',
                'nomor_induk' => '04202506007',
                'no_telepon' => '085798765432',
                'role' => 'unit',
                'alamat' => 'Jalan Anggrek, Cilacap',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rahmat',
                'email' => 'rahmatsci@gmail.com',
                'nomor_induk' => '04202506008',
                'no_telepon' => '085700011122',
                'role' => 'unit',
                'alamat' => 'Jalan Melati, Cilacap',
                'password' => Hash::make('password'),
                'foto' => 'default.jpg',
                'unit_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
