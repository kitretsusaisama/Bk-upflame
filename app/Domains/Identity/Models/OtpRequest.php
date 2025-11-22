<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OtpRequest extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'tenant_id',
        'recipient',
        'otp_hash',
        'attempts',
        'used',
        'expires_at',
        'ip',
        'user_agent'
    ];
    
    protected $casts = [
        'attempts' => 'integer',
        'used' => 'boolean',
        'expires_at' => 'datetime'
    ];
}
