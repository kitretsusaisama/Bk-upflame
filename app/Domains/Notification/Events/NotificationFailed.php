<?php

namespace App\Domains\Notification\Events;

use App\Domains\Notification\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public string $errorMessage;

    public function __construct(Notification $notification, string $errorMessage)
    {
        $this->notification = $notification;
        $this->errorMessage = $errorMessage;
    }
}