<?php

namespace App\Domains\Identity\Enums;

enum LoginMethod: string
{
    case EMAIL = 'email';
    case OTP = 'otp';
    case SSO = 'sso';
}