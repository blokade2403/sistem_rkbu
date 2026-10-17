<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\WorkFlow\WorkflowStep;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStepSubKategoriRkbu extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'workflow_step_sub_kategori_rkbu';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['workflow_step_id', 'sub_kategori_rkbu_id'];

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(
            SubKategoriRkbu::class,
            'sub_kategori_rkbu_id',
            'id_sub_kategori_rkbu',
        );
    }
}
