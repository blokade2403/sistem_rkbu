<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowInstance extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'workflow_instances';

    protected $fillable = ['workflow_id', 'dokumen_id', 'dokumen_type', 'status'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [

        'submitted_at' => 'datetime',

        'finished_at' => 'datetime'

    ];


    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'workflow_instance_id', 'id');
    }

    public function currentStep()
    {
        return $this->belongsTo(
            WorkflowStep::class,
            'current_step_id'
        );
    }

    public function document()
    {
        return $this->morphTo(
            null,
            'dokumen_type',
            'dokumen_id'
        );
    }

    public function rkbu()
    {
        return $this->belongsTo(
            Rkbu::class,
            'dokumen_id'
        );
    }

    public function currentPosition()
    {
        return $this->belongsTo(
            Position::class,
            'current_position_id'
        );
    }

    public function logs()
    {
        return $this->hasMany(
            ApprovalLog::class
        );
    }

    public function submittedBy()
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function submittedPosition()
    {
        return $this->belongsTo(
            Position::class,
            'submitted_position_id'
        );
    }

    public function submittedAssignment()
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'submitted_assignment_id'
        );
    }
}
