<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\Approval;
use App\Models\WorkFlow\ApprovalLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->abortAdministrator();

        $data = Approval::with([
            'validator',
            'validatorRole',
            'workflowStep.workflow',
            'workflowInstance',
        ])
            ->where('validator_user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('master_backend.workflow_setting.approval.index', compact('data'), [
            'title' => 'Approval - Workflow Setting',
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->abortAdministrator();

        $approval = Approval::with([
            'validator',
            'validatorRole',
            'logs.user',
            'workflowStep.workflow',
        ])->findOrFail($id);

        return view('approval.show', compact('approval'));
    }

    public function approve(Request $request, $id)
    {
        $this->abortAdministrator();

        $approval = Approval::findOrFail($id);

        $approval->update([
            'status' => 'approved',
            'catatan' => $request->catatan,
            'approved_at' => now(),
        ]);

        ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => auth()->id(),
            'aksi' => 'APPROVED',
            'catatan' => $request->catatan,
        ]);

        return back();
    }

    public function reject(Request $request, $id)
    {
        $this->abortAdministrator();

        $approval = Approval::findOrFail($id);

        $approval->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
            'approved_at' => now(),
        ]);

        ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => auth()->id(),
            'aksi' => 'REJECTED',
            'catatan' => $request->catatan,
        ]);

        return back();
    }

    public function revision(Request $request, $id)
    {
        $this->abortAdministrator();

        $approval = Approval::findOrFail($id);

        $approval->update([
            'status' => 'revision',
            'catatan' => $request->catatan,
            'approved_at' => now(),
        ]);

        ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => auth()->id(),
            'aksi' => 'REVISION',
            'catatan' => $request->catatan,
        ]);

        return back();
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function abortAdministrator(): void
    {
        abort_if(
            auth()->user()->hasRole('Administrator'),
            403,
            'Administrator hanya dapat melihat data RKBU dan tidak dapat melakukan approval.'
        );
    }
}
