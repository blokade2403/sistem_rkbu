<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\WorkFlow\WorkflowStep;
use App\Models\WorkFlow\WorkflowStepSubKategoriRkbu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkflowStepSubKategoriRkbuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = WorkflowStepSubKategoriRkbu::with([
            'workflowStep.workflow',
            'workflowStep.position',
            'workflowStep.systemRole',
            'workflowStep.validatorRole',
            'subKategoriRkbu',
        ])->get()
            ->sortBy(fn ($mapping) => strtolower((string) $mapping->subKategoriRkbu?->nama_sub_kategori_rkbu)
                . '|' . strtolower((string) $mapping->workflowStep?->workflow?->nama_workflow)
                . '|' . str_pad((string) ($mapping->workflowStep?->step_order ?? 0), 4, '0', STR_PAD_LEFT))
            ->values();

        $workflowSteps = WorkflowStep::with(['workflow', 'position', 'systemRole', 'validatorRole'])
            ->get()
            ->sortBy(fn ($step) => strtolower((string) $step->workflow?->nama_workflow)
                . '|' . str_pad((string) $step->step_order, 4, '0', STR_PAD_LEFT))
            ->values();

        $subKategoriRkbus = SubKategoriRkbu::query()->orderBy('nama_sub_kategori_rkbu')->get();

        return view(
            'master_backend.workflow_setting.workflow_step_sub_kategori_rkbu.index',
            compact('data', 'workflowSteps', 'subKategoriRkbus'),
            [
                'title' => 'Workflow Step Sub Kategori RKBU',
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'workflow_step_id' => ['required', 'uuid', Rule::exists('workflow_steps', 'id')],
            'sub_kategori_rkbu_id' => ['required', 'uuid', Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu')],
        ]);

        WorkflowStepSubKategoriRkbu::create($data);

        return redirect()->back()->with('success', 'Mapping berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        WorkflowStepSubKategoriRkbu $workflowStepSubKategoriRkbu,
    ) {
        $data = $request->validate([
            'workflow_step_id' => ['required', 'uuid', Rule::exists('workflow_steps', 'id')],
            'sub_kategori_rkbu_id' => ['required', 'uuid', Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu')],
        ]);

        $workflowStepSubKategoriRkbu->update($data);

        return redirect()->back()->with('success', 'Mapping berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkflowStepSubKategoriRkbu $workflowStepSubKategoriRkbu)
    {
        $workflowStepSubKategoriRkbu->delete();

        return redirect()->back()->with('success', 'Mapping berhasil dihapus');
    }
}
