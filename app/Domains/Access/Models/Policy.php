<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\Tenant;

class Policy extends Model
{
    use HasUuids;

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
