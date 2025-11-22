<?php

namespace App\Domains\Identity\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'otp_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'code',
        'type',
        'expires_at',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the OTP code.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tenant that owns the OTP code.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Tenant\Models\Tenant::class);
    }

    /**
     * Check if the OTP code is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the OTP code is verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}