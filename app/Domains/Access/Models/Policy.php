<?php

namespace App\Domains\Access\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class Policy extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'target_json',
        'rules_json',
        'is_enabled',
        'priority'
    ];

    protected $casts = [
        'target_json' => 'array',
        'rules_json' => 'array',
        'is_enabled' => 'boolean',
        'priority' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assignments()
    {
        return $this->hasMany(PolicyAssignment::class);
    }
}
