<?php

namespace App\Domains\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\{Tenant, User};
use App\Domains\Provider\Models\Provider;

class Booking extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'provider_id',
        'service_id',
        'booking_reference',
        'status',
        'scheduled_at',
        'duration_minutes',
        'amount',
        'currency',
        'metadata'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    public function isCancelable(): bool
    {
        return in_array($this->status, ['processing', 'confirmed']) && $this->scheduled_at->isFuture();
    }
}
