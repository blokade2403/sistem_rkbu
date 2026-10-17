<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'workflows';

    protected $fillable = ['nama_workflow', 'modul', 'kode'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class, 'workflow_id')->orderBy('step_order');
    }

    public function instances()
    {
        return $this->hasMany(
            WorkflowInstance::class
        );
    }
}
