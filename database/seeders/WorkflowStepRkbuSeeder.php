<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkflowStepRkbuSeeder extends Seeder
{
    public function run()
    {
        $workflow = DB::table('workflows')->where('modul', 'rkbu')->first();

        $pj = DB::table('positions')->where('level_jabatan', 'PJ Unit / Operator')->first();
        $ksp = DB::table('positions')->where('level_jabatan', 'KSP / Ka.Ins')->first();
        $kabag = DB::table('positions')->where('level_jabatan', 'Kabag/Kabid')->first();
        $direktur = DB::table('positions')->where('level_jabatan', 'Direktur')->first();

        DB::table('workflow_steps')->insert([
            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $pj->id,
                'step_order' => 1,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $ksp->id,
                'step_order' => 2,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $kabag->id,
                'step_order' => 3,
            ],

            [
                'id' => Str::uuid(),
                'workflow_id' => $workflow->id,
                'position_id' => $direktur->id,
                'step_order' => 4,
            ],
        ]);
    }
}
