<?php

namespace App\Domains\Workflow\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class WorkflowDefinition extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'role_family',
        'steps_json',
        'is_active'
    ];

    protected $casts = [
        'steps_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class, 'definition_id');
    }
}
