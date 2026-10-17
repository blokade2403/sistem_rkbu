<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStepPptk extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'workflow_step_pptk';

    protected $fillable = ['workflow_step_id', 'pptk_kategori_id'];

    public $incrementing = false;
    protected $keyType = 'string';

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function pptkKategori()
    {
        return $this->belongsTo(PptkKategori::class, 'pptk_kategori_id');
    }
}
