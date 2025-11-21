<?php

namespace App\Domains\Booking\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class Service extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'category',
        'base_price',
        'currency',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ServiceFactory::new();
    }
}
