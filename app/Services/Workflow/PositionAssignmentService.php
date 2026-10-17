<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\WorkFlow\WorkflowStep;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class PositionAssignmentService
{
    /**
     * Assignment aktif user
     */
    public function getUserAssignment(User $user): ?PositionAssignment
    {
        return $this->activeAssignments()
            ->where('user_id', $user->id)
            ->first();
    }

    public function getParentPosition(Position $position)
    {
        if (! $position->parent_id) {
            return null;
        }

        return Position::find($position->parent_id);
    }

    /**
     * Assignment aktif suatu jabatan
     */
    public function getAssignmentByPosition($positionId): ?PositionAssignment
    {
        return $this->activeAssignments()
            ->where('position_id', $positionId)
            ->first();
    }

    public function getCurrentAssignment(): ?PositionAssignment
    {
        return $this->activeAssignments()
            ->where('user_id', auth()->id())
            ->first();
    }

    public function validateCurrentAssignment(): PositionAssignment
    {
        $assignment = $this->getCurrentAssignment();

        if (! $assignment) {
            throw new Exception(
                'User belum memiliki jabatan aktif.'
            );
        }

        return $assignment;
    }

    public function validatePosition($positionId): PositionAssignment
    {
        $assignment =
            $this->getAssignmentByPosition(
                $positionId
            );

        if (! $assignment) {
            throw new Exception(
                'Belum ada pejabat aktif pada jabatan tersebut.'
            );
        }

        return $assignment;
    }

    public function getAssignmentByWorkflowStep(
        WorkflowStep $step
    ): ?PositionAssignment {
        return $this->getAssignmentByPosition(
            $step->position_id
        );
    }

    public function hasAssignment(User $user): bool
    {
        return $this->activeAssignments()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function activeAssignments(): Builder
    {
        return PositionAssignment::query()
            ->with(['user', 'position'])
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', today())
            ->where(function (Builder $query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            })
            ->whereHas('user', fn (Builder $query) => $query->where('status_user', 'aktif'))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('created_at');
    }
}
