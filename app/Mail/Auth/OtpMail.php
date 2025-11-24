<?php

namespace App\Mail\Auth;

use App\Domain\Identity\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public OtpCode $otpCode;

    /**
     * Create a new message instance.
     *
     * @param  OtpCode  $otpCode
     * @return void
     */
    public function __construct(OtpCode $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails.auth.otp')
                    ->with([
                        'code' => $this->otpCode->code,
                        'expiresAt' => $this->otpCode->expires_at,
                    ]);
    }
}