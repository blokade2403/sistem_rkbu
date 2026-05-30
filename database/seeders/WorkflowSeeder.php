<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkflowSeeder extends Seeder
{
    public function run()
    {
        DB::table('workflows')->insert([
            [
                'id' => Str::uuid(),
                'nama_workflow' => 'Workflow RKBU',
                'modul' => 'rkbu',
            ],

            [
                'id' => Str::uuid(),
                'nama_workflow' => 'Workflow Usulan Belanja',
                'modul' => 'usulan_belanja',
            ],
        ]);
    }
}
