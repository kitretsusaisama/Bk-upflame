<?php

namespace App\Domains\SSO\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SsoConnection extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sso_connections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider_id',
        'external_id',
        'external_email',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the SSO connection.
     */
    public function user()
    {
        return $this->belongsTo(\App\Domains\Identity\Models\User::class);
    }

    /**
     * Get the SSO provider for this connection.
     */
    public function provider()
    {
        return $this->belongsTo(SsoProvider::class);
    }
}