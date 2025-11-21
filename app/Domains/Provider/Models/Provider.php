<?php

namespace App\Domains\Provider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\{Tenant, User};
use App\Domains\Workflow\Models\Workflow;

class Provider extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'provider_type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'workflow_id',
        'profile_json'
    ];

    protected $casts = [
        'profile_json' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function documents()
    {
        return $this->hasMany(ProviderDocument::class);
    }

    public function services()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function availability()
    {
        return $this->hasMany(ProviderAvailability::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
