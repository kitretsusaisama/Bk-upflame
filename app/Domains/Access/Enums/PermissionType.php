<?php

namespace App\Domains\Access\Enums;

enum PermissionType: string
{
    case READ = 'read';
    case WRITE = 'write';
    case DELETE = 'delete';
    case CREATE = 'create';
    case UPDATE = 'update';
    case EXECUTE = 'execute';
}