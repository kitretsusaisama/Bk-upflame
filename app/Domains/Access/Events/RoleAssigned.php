<?php

namespace App\Domains\Access\Events;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Role $role;
    public User $user;

    public function __construct(Role $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }
}