<?php

namespace App\Domains\Provider\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provider_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'bio',
        'specialties',
        'years_experience',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'specialties' => 'array',
        'years_experience' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the provider that owns the profile.
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}