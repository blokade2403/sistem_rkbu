<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\Rkbu;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\Rkbu\RkbuDetailBarjas;
use App\Models\Workflow\Approval;
use App\Models\Workflow\ApprovalLog;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowInstance;
use App\Models\Workflow\WorkflowStep;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkflowApprovalService
{
    protected $positionService;

    protected $workflowService;

    public function __construct(
        PositionAssignmentService $positionService,
        WorkflowService $workflowService
    ) {
        $this->positionService = $positionService;
        $this->workflowService = $workflowService;
    }

    public function submit($id, string $detailModel = RkbuDetailBarjas::class)
    {
        DB::beginTransaction();

        try {

            /**
             * Ambil Detail RKBU
             */
            if (! is_a($detailModel, Model::class, true)) {
                throw new Exception('Model detail RKBU tidak valid.');
            }

            $detail = $detailModel::with('rkbu')->findOrFail($id);

            $rkbu = $detail->rkbu;

            if (! $rkbu) {
                throw new Exception('Data RKBU tidak ditemukan.');
            }

            if ((string) $rkbu->created_by !== (string) auth()->id()) {
                throw new Exception('Anda tidak berwenang mengajukan RKBU ini.');
            }

            if (! in_array($rkbu->status, ['draft', 'revision', 'rejected'])) {
                throw new Exception('RKBU sudah diajukan.');
            }

            /**
             * Workflow
             */
            /**
             * Ambil Workflow RKBU
             */
            $workflow = $this->workflowService->getWorkflow('RKBU');

            if (! $workflow) {
                throw new Exception('Workflow RKBU belum dibuat.');
            }

            /**
             * Ambil Step Pertama
             */
            $step = $this->workflowService->getFirstStep($workflow);

            if (! $step) {
                throw new Exception('Workflow Step belum dibuat.');
            }

            /**
             * Assignment aktif pengaju
             */
            $assignment = $this->positionService
                ->validateCurrentAssignment();

            if (! $assignment) {
                throw new Exception('Assignment user tidak ditemukan.');
            }

            /**
             * Pastikan posisi pengaju ada
             */
            $position = $assignment->position;

            if (! $position) {
                throw new Exception('Posisi user belum disetting.');
            }

            /**
             * Cari atasan langsung
             */
            $parentPosition = $this->positionService
                ->getParentPosition($position);

            if (! $parentPosition) {
                throw new Exception(
                    "Jabatan {$position->nama_jabatan} belum memiliki atasan."
                );
            }

            /**
             * Cari assignment aktif atasan
             */
            $validatorAssignment = $this->positionService
                ->getAssignmentByPosition($parentPosition->id);

            if (! $validatorAssignment) {
                throw new Exception(
                    "Belum ada user aktif pada jabatan {$parentPosition->nama_jabatan}."
                );
            }

            /**
             * Buat Workflow Instance
             */
            $instance = WorkflowInstance::create([

                'workflow_id' => $workflow->id,

                'current_step_id' => $step->id,

                'current_position_id' => $validatorAssignment->position_id,

                'dokumen_id' => $rkbu->id,

                'dokumen_type' => 'rkbu',

                'submitted_by' => auth()->id(),

                'submitted_position_id' => $assignment->position_id,

                'submitted_assignment_id' => $assignment->id,

                'submitted_at' => now(),

                'status' => 'proses',

            ]);

            /**
             * Update RKBU
             */
            $rkbu->update([

                'workflow_instance_id' => $instance->id,

                'status' => 'proses',

            ]);

            /**
             * Approval pertama
             */
            $approval = Approval::create([
                'workflow_instance_id' => $instance->id,
                'workflow_step_id' => $step->id,
                'validator_user_id' => $validatorAssignment->user_id,
                'position_id' => $validatorAssignment->position_id,
                'position_assignment_id' => $validatorAssignment->id,
                'received_at' => now(),
                'status' => 'pending',

            ]);

            /**
             * Log Submit
             */
            ApprovalLog::create([

                'approval_id' => $approval->id,

                'workflow_instance_id' => $instance->id,

                'user_id' => auth()->id(),

                'position_id' => $assignment->position_id,

                'position_assignment_id' => $assignment->id,

                'aksi' => 'submit',

                'catatan' => 'Pengajuan RKBU',

                'aksi_at' => now(),

            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RKBU berhasil diajukan.',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function createLog(
        Approval $approval,
        $aksi,
        $catatan = null
    ) {
        ApprovalLog::create([
            'approval_id' => $approval->id,
            'workflow_instance_id' => $approval->workflow_instance_id,
            'user_id' => auth()->id(),
            'position_id' => $approval->position_id,
            'position_assignment_id' => $approval->position_assignment_id,
            'aksi' => $aksi,
            'catatan' => $catatan,
            'aksi_at' => now(),
        ]);
    }

    protected function createApproval(
        WorkflowInstance $instance,
        WorkflowStep $step,
        PositionAssignment $assignment
    ) {
        return Approval::create([

            'workflow_instance_id' => $instance->id,

            'workflow_step_id' => $step->id,

            'validator_user_id' => $assignment->user_id,

            'position_id' => $assignment->position_id,

            'position_assignment_id' => $assignment->id,

            'status' => 'pending',

        ]);
    }

    protected function finishWorkflow(
        WorkflowInstance $instance
    ) {
        $instance->update([

            'status' => 'approved',

            'current_position_id' => null,

        ]);

        if ($instance->rkbu) {

            $instance->rkbu->update([

                'status' => 'approved',

            ]);
        }
    }

    // protected function findNextAssignment(
    //     Approval $approval
    // ) {
    //     $position = $approval
    //         ->assignment
    //         ->position;

    //     $parent = $this->positionService
    //         ->getParentPosition($position);

    //     if (!$parent) {

    //         return null;
    //     }

    //     return $this->positionService
    //         ->getAssignmentByPosition($parent->id);
    // }

    // public function approve(Approval $approval, $catatan = null)
    // {
    //     DB::transaction(function () use ($approval, $catatan) {
    //         $approval->update([
    //             'status' => 'approved',
    //             'approved_at' => now(),
    //             'catatan' => $catatan
    //         ]);
    //         $this->createLog(
    //             $approval,
    //             'approved',
    //             $catatan
    //         );
    //         $nextAssignment = $this->findNextAssignment(
    //             $approval
    //         );
    //         if (!$nextAssignment) {
    //             $this->finishWorkflow(
    //                 $approval->workflowInstance
    //             );

    //             return;
    //         }

    //         $nextStep = WorkflowStep::where(
    //             'workflow_id',
    //             $approval->workflowStep->workflow_id
    //         )
    //             ->where(
    //                 'step_order',
    //                 '>',
    //                 $approval->workflowStep->step_order
    //             )
    //             ->orderBy('step_order')
    //             ->first();

    //         if (!$nextStep) {

    //             $this->finishWorkflow(
    //                 $approval->workflowInstance
    //             );

    //             return;
    //         }

    //         $this->createApproval(

    //             $approval->workflowInstance,

    //             $nextStep,

    //             $nextAssignment

    //         );

    //         $approval->workflowInstance->update([

    //             'current_position_id' =>
    //             $nextAssignment->position_id,

    //             'status' => 'proses'

    //         ]);
    //     });
    // }

    public function revision(
        Approval $approval,
        $catatan
    ) {
        $approval->update([

            'status' => 'revision',

            'catatan' => $catatan,

        ]);

        $approval->workflowInstance
            ->rkbu
            ->update([

                'status' => 'revision',

            ]);

        $approval->workflowInstance
            ->update([

                'status' => 'revision',

            ]);

        $this->createLog(
            $approval,
            'revision',
            $catatan
        );
    }

    public function reject(
        Approval $approval,
        $catatan
    ) {
        $approval->update([

            'status' => 'rejected',

            'catatan' => $catatan,

        ]);

        $approval->workflowInstance
            ->rkbu
            ->update([

                'status' => 'rejected',

            ]);

        $approval->workflowInstance
            ->update([

                'status' => 'rejected',

            ]);

        $this->createLog(
            $approval,
            'rejected',
            $catatan
        );
    }
}
