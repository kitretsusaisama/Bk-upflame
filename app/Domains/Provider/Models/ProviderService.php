<?php

namespace App\Domains\Provider\Models;

use App\Support\BaseModel;

class ProviderService extends BaseModel
{

    protected $fillable = [
        'provider_id',
        'service_name',
        'description',
        'price',
        'currency',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
