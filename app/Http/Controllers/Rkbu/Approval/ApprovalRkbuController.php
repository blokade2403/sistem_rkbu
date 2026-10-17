<?php

namespace App\Http\Controllers\Rkbu\Approval;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\Rkbu\Rkbu;
use App\Models\WorkFlow\Approval;
use App\Models\WorkFlow\ApprovalLog;
use App\Models\WorkFlow\WorkflowInstance;
use App\Models\WorkFlow\WorkflowStep;
use App\Services\Workflow\PositionAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalRkbuController extends Controller
{
    public function approve(Request $request, $approvalId)
    {
        DB::beginTransaction();

        try {
            $assignment = $this->currentAssignment();
            $approval = $this->approvalForCurrentYear($approvalId, $assignment);

            $approval->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            ApprovalLog::create([
                'approval_id' => $approval->id,
                'workflow_instance_id' => $approval->workflow_instance_id,
                'user_id' => auth()->id(),
                'position_id' => $assignment->position_id,
                'position_assignment_id' => $assignment->id,
                'aksi' => 'approved',
                'catatan' => $request->catatan,
                'aksi_at' => now(),
            ]);

            $currentStep = WorkflowStep::find(
                $approval->workflow_step_id
            );

            $nextStep = WorkflowStep::where(
                'workflow_id',
                $currentStep->workflow_id
            )
                ->where(
                    'step_order',
                    '>',
                    $currentStep->step_order
                )
                ->orderBy('step_order')
                ->first();

            if ($nextStep) {
                $positionId = $nextStep->position_id ?: $assignment->position?->parent_id;
                $nextAssignment = $positionId
                    ? app(PositionAssignmentService::class)->getAssignmentByPosition($positionId)
                    : null;

                if (! $nextAssignment) {
                    throw new \Exception('Assignment pejabat aktif untuk langkah approval berikutnya tidak ditemukan.');
                }

                Approval::create([
                    'workflow_instance_id' => $approval->workflow_instance_id,
                    'workflow_step_id' => $nextStep->id,
                    'validator_user_id' => $nextAssignment->user_id,
                    'position_id' => $nextAssignment->position_id,
                    'position_assignment_id' => $nextAssignment->id,
                    'received_at' => now(),
                    'status' => 'pending',
                ]);

                $approval->workflowInstance->update([
                    'status' => 'proses',
                    'current_step_id' => $nextStep->id,
                    'current_position_id' => $nextAssignment->position_id,
                ]);
            } else {

                $instance = WorkflowInstance::find(
                    $approval->workflow_instance_id
                );

                $instance->update([
                    'status' => 'approved',
                ]);

                Rkbu::where(
                    'workflow_instance_id',
                    $instance->id
                )->update([
                    'status' => 'approved',
                ]);
            }

            DB::commit();

            return back();
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function revision(Request $request, $approvalId)
    {
        $assignment = $this->currentAssignment();
        $approval = $this->approvalForCurrentYear($approvalId, $assignment);

        $approval->update([
            'status' => 'revision',
            'catatan' => $request->catatan,
        ]);
        $this->logAction($approval, $assignment, 'revision', $request->catatan);

        $instance = WorkflowInstance::find(
            $approval->workflow_instance_id
        );

        $instance->update([
            'status' => 'draft',
        ]);

        Rkbu::where(
            'workflow_instance_id',
            $instance->id
        )->update([
            'status' => 'revision',
        ]);
    }

    public function reject(Request $request, $approvalId)
    {
        $assignment = $this->currentAssignment();
        $approval = $this->approvalForCurrentYear($approvalId, $assignment);

        $approval->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
        ]);
        $this->logAction($approval, $assignment, 'rejected', $request->catatan);

        $instance = WorkflowInstance::find(
            $approval->workflow_instance_id
        );

        $instance->update([
            'status' => 'rejected',
        ]);

        Rkbu::where(
            'workflow_instance_id',
            $instance->id
        )->update([
            'status' => 'rejected',
        ]);
    }

    private function currentAssignment(): PositionAssignment
    {
        abort_if(
            auth()->user()->hasRole('Administrator'),
            403,
            'Administrator hanya dapat melihat data RKBU dan tidak dapat melakukan approval.'
        );

        $assignment = app(PositionAssignmentService::class)->getCurrentAssignment();

        abort_unless($assignment, 403, 'User tidak memiliki assignment jabatan yang aktif.');

        return $assignment;
    }

    private function approvalForCurrentYear(
        string $approvalId,
        PositionAssignment $assignment
    ): Approval {
        return Approval::query()
            ->whereHas('workflowInstance.rkbu', function ($query) {
                $query->where('tahun_anggaran_id', session('tahun_anggaran_id'));
            })
            ->where(function ($query) use ($assignment) {
                $query->where('position_id', $assignment->position_id)
                    ->orWhere(function ($legacyQuery) use ($assignment) {
                        $legacyQuery->whereNull('position_id')
                            ->whereHas(
                                'workflowInstance',
                                fn ($instanceQuery) => $instanceQuery->where(
                                    'current_position_id',
                                    $assignment->position_id
                                )
                            );
                    });
            })
            ->where('status', 'pending')
            ->findOrFail($approvalId);
    }

    private function logAction(
        Approval $approval,
        PositionAssignment $assignment,
        string $action,
        ?string $note
    ): void {
        ApprovalLog::create([
            'approval_id' => $approval->id,
            'workflow_instance_id' => $approval->workflow_instance_id,
            'user_id' => auth()->id(),
            'position_id' => $assignment->position_id,
            'position_assignment_id' => $assignment->id,
            'aksi' => $action,
            'catatan' => $note,
            'aksi_at' => now(),
        ]);
    }
}
