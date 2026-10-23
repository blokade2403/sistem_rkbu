<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\ValidatorRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'workflow_steps';

    protected $fillable = [
        'workflow_id',
        'validator_type',
        'hierarchy_level',
        'position_id',
        'role_id',
        'validator_role_id',
        'step_order',
        'approval_type',
        'is_required',
        'is_pptk_step'
    ];

    public const TYPE_HIERARCHY = 'hierarchy';
    public const TYPE_POSITION  = 'position';
    public const TYPE_ROLE      = 'role';
    public const TYPE_USER      = 'user';
    public $incrementing = false;

    protected $keyType = 'string';

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function role()
    {
        return $this->belongsTo(
            ValidatorRole::class,
            'validator_role_id'
        );
    }

    public function systemRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function validatorRole()
    {
        return $this->belongsTo(
            ValidatorRole::class,
            'validator_role_id'
        );
    }


    public function position()
    {
        return $this->belongsTo(
            Position::class,
            'position_id'
        );
    }

    public function approvals()
    {
        return $this->hasMany(
            Approval::class
        );
    }

    public function subKategoriRkbus()
    {
        return $this->belongsToMany(
            \App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu::class,
            'workflow_step_sub_kategori_rkbu',
            'workflow_step_id',
            'sub_kategori_rkbu_id',
            'id',
            'id_sub_kategori_rkbu'
        );
    }
}
