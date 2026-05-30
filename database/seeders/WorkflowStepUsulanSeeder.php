<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkflowStepUsulanSeeder extends Seeder
{
    public function run()
    {
        $workflow = DB::table('workflows')->where('modul', 'usulan_belanja')->first();

        $ksp = DB::table('positions')->where('nama_jabatan', 'KSP / Ka.Ins')->first();
        $perencana = DB::table('positions')->where('nama_jabatan', 'Perencana')->first();
        $pptk = DB::table('positions')->where('nama_jabatan', 'PPTK')->first();
        $kabag = DB::table('positions')->where('nama_jabatan', 'Kabag/Kabid')->first();
        $ppk = DB::table('positions')->where('nama_jabatan', 'PPK Keuangan')->first();
        $direktur = DB::table('positions')->where('nama_jabatan', 'Direktur')->first();

        DB::table('workflow_steps')->insert([
            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $ksp->id,
                'step_order' => 1,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $perencana->id,
                'step_order' => 2,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $pptk->id,
                'step_order' => 3,
                'is_pptk_step' => 1,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $kabag->id,
                'step_order' => 4,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $ppk->id,
                'step_order' => 5,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $direktur->id,
                'step_order' => 6,
            ],
        ]);
    }
}
