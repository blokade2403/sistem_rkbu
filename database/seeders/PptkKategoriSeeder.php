<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PptkKategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('pptk_kategoris')->insert([
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'PPTK Umum',
            ],
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'PPTK Pelayanan Medis',
            ],
            [
                'id' => Str::uuid(),
                'nama_kategori' => 'PPTK Penunjang',
            ],
        ]);
    }
}
