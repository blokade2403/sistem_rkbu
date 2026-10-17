<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    use HasUuids;

    protected $fillable = [

        'approval_id',

        'workflow_instance_id',

        'user_id',

        'position_id',

        'position_assignment_id',

        'aksi',

        'catatan',

        'aksi_at'

    ];

    protected $casts = [

        'aksi_at' => 'datetime'

    ];

    public function approval()
    {
        return $this->belongsTo(
            Approval::class
        );
    }

    public function workflowInstance()
    {
        return $this->belongsTo(
            WorkflowInstance::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
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
