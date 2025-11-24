<?php

namespace App\Domains\Workflow\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;

class WorkflowInstance extends BaseModel
{
    protected $fillable = [
        'tenant_id',
        'workflow_id',
        'user_id',
        'entity_type',
        'entity_id',
        'status',
        'started_at',
        'completed_at',
        'context_json'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'context_json' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(WorkflowStepInstance::class, 'instance_id');
    }

    public function currentStep()
    {
        return $this->hasOne(WorkflowStepInstance::class, 'instance_id')
            ->where('status', 'active');
    }
}