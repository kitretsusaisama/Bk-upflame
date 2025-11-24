<?php

namespace App\Domains\Booking\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case RESCHEDULED = 'rescheduled';
    case NO_SHOW = 'no_show';
}