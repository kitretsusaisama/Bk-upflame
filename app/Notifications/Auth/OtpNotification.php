<?php

namespace App\Notifications\Auth;

use App\Domain\Identity\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public OtpCode $otpCode;

    /**
     * Create a new notification instance.
     *
     * @param  OtpCode  $otpCode
     * @return void
     */
    public function __construct(OtpCode $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Mail\Auth\OtpMail
     */
    public function toMail($notifiable)
    {
        return new \App\Mail\Auth\OtpMail($this->otpCode);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'code' => $this->otpCode->code,
            'expires_at' => $this->otpCode->expires_at,
            'type' => 'otp',
        ];
    }
}