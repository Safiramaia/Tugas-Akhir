<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('unit_kerja')->insert([
            ['nama_unit' => 'IT',
             'created_at' => now(),
             'updated_at' => now(),
            ],
            ['nama_unit' => 'Laboratorium',
             'created_at' => now(),
             'updated_at' => now(),
            ],
            ['nama_unit' => 'Operasional',
             'created_at' => now(),
             'updated_at' => now(),
            ],
            ['nama_unit' => 'Administrasi',
             'created_at' => now(),
             'updated_at' => now(),
            ],
            ['nama_unit' => 'Umum',
             'created_at' => now(),
             'updated_at' => now(),
            ],
        ]);
    }
}
