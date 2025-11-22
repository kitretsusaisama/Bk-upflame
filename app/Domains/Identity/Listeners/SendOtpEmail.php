<?php

namespace App\Domains\Identity\Listeners;

use App\Domains\Identity\Events\OtpGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOtpEmail
{
    use InteractsWithQueue;

    public function handle(OtpGenerated $event)
    {
        // Send OTP code to the user via email
        // This would typically involve:
        // 1. Formatting the OTP message
        // 2. Sending the email using the Mailer
        // 3. Logging the OTP sending attempt
        
        // Implementation would go here
    }
}