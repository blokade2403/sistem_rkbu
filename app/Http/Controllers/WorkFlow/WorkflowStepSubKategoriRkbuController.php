<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\WorkFlow\WorkflowStep;
use App\Models\WorkFlow\WorkflowStepSubKategoriRkbu;
use Illuminate\Http\Request;

class WorkflowStepSubKategoriRkbuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = WorkflowStepSubKategoriRkbu::with([
            'workflowStep.workflow',
            'subKategoriRkbu',
        ])->get();

        $workflowSteps = WorkflowStep::with('workflow')->get();

        $subKategoriRkbus = SubKategoriRkbu::all();

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
        $request->validate([
            'workflow_step_id' => 'required',
            'sub_kategori_rkbu_id' => 'required',
        ]);

        WorkflowStepSubKategoriRkbu::create([
            'workflow_step_id' => $request->workflow_step_id,
            'sub_kategori_rkbu_id' => $request->sub_kategori_rkbu_id,
        ]);

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
        $request->validate([
            'workflow_step_id' => 'required',
            'sub_kategori_rkbu_id' => 'required',
        ]);

        $workflowStepSubKategoriRkbu->update([
            'workflow_step_id' => $request->workflow_step_id,
            'sub_kategori_rkbu_id' => $request->sub_kategori_rkbu_id,
        ]);

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
