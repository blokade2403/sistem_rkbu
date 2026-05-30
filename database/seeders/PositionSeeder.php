<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $pendukung_ppk = Str::uuid();
        $pb = Str::uuid();
        $ppbj = Str::uuid();
        $ppk = Str::uuid();
        $pptk = Str::uuid();
        $verifikator = Str::uuid();
        $ppk_keu = Str::uuid();
        $direktur = Str::uuid();
        $bendahara = Str::uuid();
        $kabag = Str::uuid();
        $ksp = Str::uuid();
        $pj = Str::uuid();
        $perencana = Str::uuid();
        $admin_ppk = Str::uuid();

        DB::table('positions')->insert([
            [
                'id' => $direktur,
                'level_jabatan' => 'Direktur',
                'parent_id' => null,
            ],

            [
                'id' => $kabag,
                'level_jabatan' => 'Kabag/Kabid',
                'parent_id' => $direktur,
            ],

            [
                'id' => $ksp,
                'level_jabatan' => 'KSP / Ka.Ins',
                'parent_id' => $kabag,
            ],

            [
                'id' => $pj,
                'level_jabatan' => 'PJ Unit / Operator',
                'parent_id' => $ksp,
            ],

            [
                'id' => $perencana,
                'level_jabatan' => 'Perencana',
                'parent_id' => $kabag,
            ],

            [
                'id' => $pptk,
                'level_jabatan' => 'PPTK',
                'parent_id' => $direktur,
            ],

            [
                'id' => $ppk,
                'level_jabatan' => 'PPK',
                'parent_id' => $direktur,
            ],
            [
                'id' => $ppbj,
                'level_jabatan' => 'PPBJ',
                'parent_id' => $ppk,
            ],
            [
                'id' => $pendukung_ppk,
                'level_jabatan' => 'Pendukung PPK',
                'parent_id' => $ppk,
            ],
            [
                'id' => $verifikator,
                'level_jabatan' => 'Verifikator',
                'parent_id' => $ppk,
            ],
            [
                'id' => $ppk_keu,
                'level_jabatan' => 'PPK Keuangan',
                'parent_id' => $direktur,
            ],
            [
                'id' => $bendahara,
                'level_jabatan' => 'Bendahara',
                'parent_id' => $direktur,
            ],
            [
                'id' => $admin_ppk,
                'level_jabatan' => 'Admin PPK',
                'parent_id' => $ppk,
            ],
            [
                'id' => $pb,
                'level_jabatan' => 'Pengurus Barang',
                'parent_id' => $direktur,
            ],
        ]);
    }
}
