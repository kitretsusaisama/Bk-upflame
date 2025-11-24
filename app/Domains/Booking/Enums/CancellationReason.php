<?php

namespace App\Domains\Booking\Enums;

enum CancellationReason: string
{
    case USER_REQUEST = 'user_request';
    case PROVIDER_UNAVAILABLE = 'provider_unavailable';
    case SYSTEM_ERROR = 'system_error';
    case OTHER = 'other';
}