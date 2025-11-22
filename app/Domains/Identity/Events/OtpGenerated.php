<?php

namespace App\Domains\Identity\Events;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $otpCode;
    public string $channel;

    public function __construct(User $user, string $otpCode, string $channel)
    {
        $this->user = $user;
        $this->otpCode = $otpCode;
        $this->channel = $channel;
    }
}