<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workflows = Workflow::with('steps')->get();

        return view('master_backend.workflow_setting.workflow.index', compact('workflows'), [
            'title' => 'Workflow',
            'title2' => 'Master Workflow',
            'routePrefix' => 'workflows',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_workflow' => 'required',
            'modul' => 'required',
            'kode' => 'required|unique:workflows',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Workflow::create([
            'nama_workflow' => $request->nama_workflow,
            'modul' => $request->modul,
            'kode' => $request->kode,
        ]);

        return redirect()
            ->route('workflows.index')
            ->with('success', 'Workflow berhasil ditambahkan');
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
    public function update(Request $request, Workflow $workflow)
    {
        $validator = Validator::make($request->all(), [
            'nama_workflow' => 'required',
            'modul' => 'required',
            'kode' => 'required|unique:workflows,kode,' . $workflow->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $workflow->update([
            'nama_workflow' => $request->nama_workflow,
            'modul' => $request->modul,
            'kode' => $request->kode,
        ]);

        return redirect()->route('workflows.index')->with('success', 'Workflow berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workflow $workflow)
    {
        $workflow->delete();

        return redirect()->route('workflows.index')->with('success', 'Workflow berhasil dihapus');
    }
}
