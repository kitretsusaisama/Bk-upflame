<?php

namespace App\Observers;

use App\Domain\Booking\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return void
     */
    public function created(Booking $booking)
    {
        // Handle booking creation
        // For example, send a confirmation notification
    }

    /**
     * Handle the Booking "updated" event.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return void
     */
    public function updated(Booking $booking)
    {
        // Handle booking update
        // For example, if status changes, send appropriate notifications
    }

    /**
     * Handle the Booking "deleted" event.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return void
     */
    public function deleted(Booking $booking)
    {
        // Handle booking deletion
    }

    /**
     * Handle the Booking "restored" event.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return void
     */
    public function restored(Booking $booking)
    {
        // Handle booking restoration
    }

    /**
     * Handle the Booking "force deleted" event.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return void
     */
    public function forceDeleted(Booking $booking)
    {
        // Handle force deletion
    }
}