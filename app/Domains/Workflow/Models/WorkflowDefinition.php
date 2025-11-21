<?php

namespace App\Domains\Workflow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\Tenant;

class WorkflowDefinition extends Model
{
    use HasUuids;

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
