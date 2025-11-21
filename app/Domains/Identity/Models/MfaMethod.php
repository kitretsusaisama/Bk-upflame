<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;

class MfaMethod extends BaseModel
{

    protected $fillable = [
        'user_id',
        'type',
        'secret_encrypted',
        'is_primary',
        'is_verified'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
