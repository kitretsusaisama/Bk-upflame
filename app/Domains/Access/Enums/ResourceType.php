<?php

namespace App\Domains\Access\Enums;

enum ResourceType: string
{
    case PROVIDER = 'provider';
    case BOOKING = 'booking';
    case WORKFLOW = 'workflow';
    case USER = 'user';
    case ROLE = 'role';
    case PERMISSION = 'permission';
    case TENANT = 'tenant';
}