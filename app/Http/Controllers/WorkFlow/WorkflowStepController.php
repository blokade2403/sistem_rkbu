<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\ValidatorRole;
use App\Models\WorkFlow\Workflow;
use App\Models\WorkFlow\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkflowStepController extends Controller
{
    public function index()
    {
        $workflowSteps = WorkflowStep::with([
            'workflow',
            'position',
            'systemRole',
            'validatorRole',
            'subKategoriRkbus',
        ])
            ->orderBy('workflow_id')
            ->orderBy('step_order')
            ->get();

        $workflows = Workflow::all();
        $levelJabatans = Position::select('level_jabatan')
            ->distinct()
            ->orderBy('level_jabatan')
            ->get();
        $roles = Role::all();
        $validatorRoles = ValidatorRole::all();
        $positions = Position::orderBy('nama_jabatan')->get();


        return view(
            'master_backend.workflow_setting.workflow_step.index',
            compact('workflowSteps', 'workflows', 'levelJabatans', 'roles', 'validatorRoles', 'positions'),
            [
                'title' => 'Workflow Step',
                'title2' => 'Master Workflow Step',
                'routePrefix' => 'workflow_steps',
                'validatorTypes' => [
                    'hierarchy' => 'Hierarchy',
                    'position' => 'Position',
                    'role' => 'Role',
                    'user' => 'User'
                ]
            ],
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workflow_id' => 'required|exists:workflows,id',
            'validator_type' => 'required|in:hierarchy,position,role,user',
            'hierarchy_level' => 'nullable|integer|min:1',
            'position_id' => 'nullable|exists:positions,id',
            'role_id' => 'nullable|exists:roles,id',
            'validator_role_id' => 'nullable|exists:validator_roles,id',
            'step_order' => 'required|integer|min:1',
            'approval_type' => 'required|in:sequential,parallel'

        ]);

        if ($request->validator_type == 'position') {
            $request->validate([
                'position_id' => 'required|exists:positions,id'
            ]);
        }

        if ($request->validator_type == 'hierarchy') {
            $request->validate([
                'hierarchy_level' => 'required|integer|min:1'
            ]);
        }

        if ($request->validator_type == 'role') {
            $request->validate([
                'role_id' => 'required|exists:roles,id'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        WorkflowStep::create([
            'workflow_id' => $request->workflow_id,
            'validator_type' => $request->validator_type,
            'hierarchy_level' => $request->hierarchy_level,
            'position_id' => $request->position_id,
            'role_id' => $request->role_id,
            'validator_role_id' => $request->validator_role_id,
            'step_order' => $request->step_order,
            'approval_type' => $request->approval_type,
            'is_required' => $request->boolean('is_required'),
            'is_pptk_step' => $request->boolean('is_pptk_step')
        ]);

        return redirect()
            ->route('workflow_steps.index')
            ->with('success', 'Workflow Step berhasil ditambahkan');
    }

    public function update(Request $request, WorkflowStep $workflowStep)
    {

        $validator = Validator::make($request->all(), [
            'workflow_id' => 'required|exists:workflows,id',
            'validator_type' => 'required|in:hierarchy,position,role,user',
            'hierarchy_level' => 'nullable|integer|min:1',
            'position_id' => 'nullable|exists:positions,id',
            'role_id' => 'nullable|exists:roles,id',
            'validator_role_id' => 'nullable|exists:validator_roles,id',
            'step_order' => 'required|integer|min:1',
            'approval_type' => 'required|in:sequential,parallel'

        ]);

        if ($request->validator_type == 'position') {
            $request->validate([
                'position_id' => 'required|exists:positions,id'
            ]);
        }

        if ($request->validator_type == 'hierarchy') {
            $request->validate([
                'hierarchy_level' => 'required|integer|min:1'
            ]);
        }

        if ($request->validator_type == 'role') {
            $request->validate([
                'role_id' => 'required|exists:roles,id'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $workflowStep->update([
            'workflow_id' => $request->workflow_id,
            'validator_type' => $request->validator_type,
            'hierarchy_level' => $request->hierarchy_level,
            'position_id' => $request->position_id,
            'role_id' => $request->role_id,
            'validator_role_id' => $request->validator_role_id,
            'step_order' => $request->step_order,
            'approval_type' => $request->approval_type,
            'is_required' => $request->boolean('is_required'),
            'is_pptk_step' => $request->boolean('is_pptk_step')
        ]);

        return redirect()
            ->route('workflow_steps.index')
            ->with('success', 'Workflow Step berhasil diupdate');
    }

    public function destroy(WorkflowStep $workflowStep)
    {
        $workflowStep->delete();

        return redirect()
            ->route('workflow_steps.index')
            ->with('success', 'Workflow Step berhasil dihapus');
    }
}
