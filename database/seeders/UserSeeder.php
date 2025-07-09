<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'no_telepon' => '085713333394',
            'role' => 'admin', 
            'nomor_induk' => '01202504001',    
            'alamat' => 'Jalan Nangka, Cilacap',
            'foto' => 'default.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
