<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;

class UserProfile extends BaseModel
{

    protected $fillable = [
        'user_id',
        'tenant_id',
        'first_name',
        'last_name',
        'phone',
        'avatar_url',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    protected static function newFactory()
    {
        return \Database\Factories\UserProfileFactory::new();
    }
}
