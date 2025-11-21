<?php

namespace App\Domains\Workflow\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class Workflow extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'definition_id',
        'entity_type',
        'entity_id',
        'current_step_key',
        'status',
        'context_json'
    ];

    protected $casts = [
        'context_json' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function definition()
    {
        return $this->belongsTo(WorkflowDefinition::class, 'definition_id');
    }

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function events()
    {
        return $this->hasMany(WorkflowEvent::class);
    }

    public function currentStep()
    {
        return $this->hasOne(WorkflowStep::class)->where('step_key', $this->current_step_key);
    }
}
