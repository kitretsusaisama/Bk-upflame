<?php

namespace App\Domains\SSO\Events;

use App\Domains\SSO\Models\SsoConnection;
use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SsoLoginCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SsoConnection $connection;
    public User $user;

    public function __construct(SsoConnection $connection, User $user)
    {
        $this->connection = $connection;
        $this->user = $user;
    }
}