<?php

namespace App\Domains\Provider\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\User;

class ProviderDocument extends BaseModel
{

    protected $fillable = [
        'provider_id',
        'document_type',
        'file_id',
        'file_url',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
