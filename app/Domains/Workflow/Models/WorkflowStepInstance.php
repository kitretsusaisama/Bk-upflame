<?php

namespace App\Domains\Workflow\Models;

use App\Support\BaseModel;

class WorkflowStepInstance extends BaseModel
{
    protected $fillable = [
        'instance_id',
        'step_id',
        'status',
        'started_at',
        'completed_at',
        'data_json'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'data_json' => 'array',
    ];

    public function instance()
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class);
    }
}