<?php

namespace App\Domains\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\{Tenant, User};

class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'template_id',
        'recipient_user_id',
        'recipient_email',
        'recipient_phone',
        'channel',
        'subject',
        'body',
        'status',
        'priority',
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'failure_reason',
        'metadata'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function recipientUser()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
