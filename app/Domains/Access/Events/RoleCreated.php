<?php

namespace App\Domains\Access\Events;

use App\Domains\Access\Models\Role;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}