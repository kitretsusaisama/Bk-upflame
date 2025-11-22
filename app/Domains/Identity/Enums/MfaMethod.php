<?php

namespace App\Domains\Identity\Enums;

enum MfaMethod: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case AUTH_APP = 'auth_app';
}