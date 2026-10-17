<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\PptkKategori;
use App\Models\WorkFlow\WorkflowStep;
use App\Models\WorkFlow\WorkflowStepPptk;
use Illuminate\Http\Request;

class WorkflowStepPptkController extends Controller
{
    public function index()
    {
        $workflowStepPptks = WorkflowStepPptk::with([
            'workflowStep.workflow',
            'pptkKategori',
        ])->get();

        $workflowSteps = WorkflowStep::with('workflow')->get();
        $pptkKategoris = PptkKategori::all();

        return view(
            'master_backend.workflow_setting.workflow_step_pptk.index',
            compact('workflowStepPptks', 'workflowSteps', 'pptkKategoris'),
            [
                'title' => 'Workflow Step PPTK',
                'title2' => 'Master Workflow Step PPTK',
                'routePrefix' => 'workflow_step_pptks',
            ],
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'workflow_step_id' => 'required',
            'pptk_kategori_id' => 'required',
        ]);

        WorkflowStepPptk::create([
            'workflow_step_id' => $request->workflow_step_id,
            'pptk_kategori_id' => $request->pptk_kategori_id,
        ]);

        return back()->with('success', 'Mapping berhasil dibuat');
    }

    public function destroy(WorkflowStepPptk $workflowStepPptk)
    {
        $workflowStepPptk->delete();

        return back()->with('success', 'Mapping berhasil dihapus');
    }
}
