<?php

namespace App\Domains\Workflow\Enums;

enum WorkflowStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';
}