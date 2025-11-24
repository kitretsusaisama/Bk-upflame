<?php

namespace App\Domains\Provider\Events;

use App\Domains\Provider\Models\Provider;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProviderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }
}