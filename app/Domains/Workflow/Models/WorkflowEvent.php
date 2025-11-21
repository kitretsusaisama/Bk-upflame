<?php

namespace App\Domains\Workflow\Models;

use App\Support\BaseModel;

class WorkflowEvent extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        'workflow_id',
        'step_id',
        'event_type',
        'payload_json',
        'created_at'
    ];

    protected $casts = [
        'payload_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class, 'step_id');
    }
}
