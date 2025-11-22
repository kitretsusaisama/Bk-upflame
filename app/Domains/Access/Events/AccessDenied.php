<?php

namespace App\Domains\Access\Events;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccessDenied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $resource;
    public string $action;

    public function __construct(User $user, string $resource, string $action)
    {
        $this->user = $user;
        $this->resource = $resource;
        $this->action = $action;
    }
}