<?php

namespace App\Domains\Access\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class PolicyAssignment extends BaseModel
{

    protected $fillable = [
        'policy_id',
        'assignee_type',
        'assignee_id',
        'tenant_id'
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
