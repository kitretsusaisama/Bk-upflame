<?php

namespace App\Domains\Notification\Enums;

enum NotificationChannel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
    case SLACK = 'slack';
    case WEBHOOK = 'webhook';
}