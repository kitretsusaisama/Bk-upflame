<?php

namespace App\Domains\SSO\Events;

use App\Domains\SSO\Models\SsoConnection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SsoGroupsSynced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SsoConnection $connection;
    public array $groups;

    public function __construct(SsoConnection $connection, array $groups)
    {
        $this->connection = $connection;
        $this->groups = $groups;
    }
}