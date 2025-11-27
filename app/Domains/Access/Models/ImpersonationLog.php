<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\Tenant;

class ImpersonationLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'impersonator_id',
        'impersonated_id',
        'tenant_id',
        'started_at',
        'ended_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function impersonator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }

    public function impersonated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'impersonated_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
