<?php

namespace App\Domains\Access\Events;

use App\Domains\Access\Models\Permission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Permission $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
}