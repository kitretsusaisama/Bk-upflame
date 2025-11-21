<?php

namespace App\Domains\Provider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\User;

class ProviderDocument extends Model
{
    use HasUuids;

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
