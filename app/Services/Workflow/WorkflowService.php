<?php

namespace App\Services\Workflow;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStep;

class WorkflowService
{
    /**
     * Ambil workflow berdasarkan modul.
     */
    public function getWorkflow(string $modul): Workflow
    {
        return Workflow::where('modul', strtoupper($modul))
            ->firstOrFail();
    }

    /**
     * Step pertama workflow.
     */
    public function getFirstStep(Workflow $workflow): WorkflowStep
    {
        return WorkflowStep::where('workflow_id', $workflow->id)
            ->orderBy('step_order')
            ->firstOrFail();
    }
}
