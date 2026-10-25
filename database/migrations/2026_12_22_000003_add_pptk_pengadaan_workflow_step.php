<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const WORKFLOW_NAME = 'Workflow Pengadaan';
    private const STEP_ID = 'a4c1a7c2-17aa-4e4e-84c3-95b303a4619d';

    public function up(): void
    {
        $workflowId = DB::table('workflows')->where('nama_workflow', self::WORKFLOW_NAME)->value('id');
        if (! $workflowId || DB::table('workflow_steps')->where('workflow_id', $workflowId)->where('is_pptk_step', true)->exists()) {
            return;
        }

        DB::table('workflow_steps')->where('workflow_id', $workflowId)->where('step_order', '>=', 5)->increment('step_order');
        DB::table('workflow_steps')->insert([
            'id' => self::STEP_ID,
            'workflow_id' => $workflowId,
            'validator_type' => 'hierarchy',
            'hierarchy_level' => 1,
            'position_id' => null,
            'role_id' => null,
            'validator_role_id' => null,
            'step_order' => 5,
            'approval_type' => 'SEQUENTIAL',
            'is_required' => true,
            'is_pptk_step' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $workflowId = DB::table('workflows')->where('nama_workflow', self::WORKFLOW_NAME)->value('id');
        if (! $workflowId) return;

        $deleted = DB::table('workflow_steps')->where('id', self::STEP_ID)->where('workflow_id', $workflowId)->delete();
        if ($deleted) {
            DB::table('workflow_steps')->where('workflow_id', $workflowId)->where('step_order', '>', 5)->decrement('step_order');
        }
    }
};
