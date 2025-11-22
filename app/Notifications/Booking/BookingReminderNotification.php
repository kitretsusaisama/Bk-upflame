<?php

namespace App\Notifications\Booking;

use App\Domain\Booking\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingReminderNotification extends Notification
{
    use Queueable;

    public Booking $booking;

    /**
     * Create a new notification instance.
     *
     * @param  Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
     * @return \App\Mail\Booking\BookingConfirmationMail
     */
    public function toMail($notifiable)
    {
        return new \App\Mail\Booking\BookingConfirmationMail($this->booking);
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
            'booking_id' => $this->booking->id,
            'scheduled_at' => $this->booking->scheduled_at,
            'type' => 'booking_reminder',
        ];
    }
}