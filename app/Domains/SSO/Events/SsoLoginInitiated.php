<?php

namespace App\Domains\SSO\Events;

use App\Domains\SSO\Models\SsoProvider;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SsoLoginInitiated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SsoProvider $provider;
    public string $redirectUrl;

    public function __construct(SsoProvider $provider, string $redirectUrl)
    {
        $this->provider = $provider;
        $this->redirectUrl = $redirectUrl;
    }
}