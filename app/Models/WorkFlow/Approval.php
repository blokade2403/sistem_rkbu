<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\ValidatorRole;
use App\Models\WorkFlow\WorkflowInstance;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'workflow_instance_id',
        'workflow_step_id',
        'validator_user_id',
        'position_id',
        'position_assignment_id',
        'status',
        'received_at',
        'approved_at',
        'closed_at',
        'catatan',
        'sequence',
        'is_current'
    ];

    protected $casts = [

        'received_at' => 'datetime',

        'approved_at' => 'datetime',

        'closed_at' => 'datetime'

    ];

    public function workflowInstance()
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function validatorRole()
    {
        return $this->belongsTo(ValidatorRole::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_user_id');
    }

    public function logs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    public function validatorUser()
    {
        return $this->belongsTo(
            User::class,
            'validator_user_id',
            'id'
        );
    }

    public function position()
    {
        return $this->belongsTo(
            Position::class
        );
    }

    public function assignment()
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'position_assignment_id'
        );
    }
}
