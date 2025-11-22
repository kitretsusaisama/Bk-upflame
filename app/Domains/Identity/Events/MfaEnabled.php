<?php

namespace App\Domains\Identity\Events;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MfaEnabled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $method;

    public function __construct(User $user, string $method)
    {
        $this->user = $user;
        $this->method = $method;
    }
}