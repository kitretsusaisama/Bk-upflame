<?php

namespace App\Domains\Provider\Events;

use App\Domains\Provider\Models\Provider;
use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProviderRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Provider $provider;
    public User $user;
    public int $rating;

    public function __construct(Provider $provider, User $user, int $rating)
    {
        $this->provider = $provider;
        $this->user = $user;
        $this->rating = $rating;
    }
}