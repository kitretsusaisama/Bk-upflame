<?php

namespace App\Domains\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\Tenant;

class NotificationTemplate extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'channel',
        'subject',
        'body',
        'variables_json',
        'is_active'
    ];

    protected $casts = [
        'variables_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'template_id');
    }
}
