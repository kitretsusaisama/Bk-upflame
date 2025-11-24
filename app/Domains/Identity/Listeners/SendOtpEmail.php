<?php

namespace App\Domains\Identity\Listeners;

use App\Domains\Identity\Events\OtpGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\OtpMail;
use Illuminate\Support\Facades\Log;

class SendOtpEmail
{
    use InteractsWithQueue;

    public function handle(OtpGenerated $event)
    {
        try {
            // Send OTP code to the user via email
            Mail::to($event->user->email)->send(new OtpMail($event->user, $event->otpCode));
            
            // Log the OTP sending attempt
            Log::info('OTP sent to user', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            // Log any errors that occur during sending
            Log::error('Failed to send OTP email', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'error' => $e->getMessage(),
                'timestamp' => now()
            ]);
        }
    }
}