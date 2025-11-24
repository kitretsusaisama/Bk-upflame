<?php

namespace App\Domains\Workflow\Enums;

enum StepType: string
{
    case MANUAL = 'manual';
    case AUTOMATIC = 'automatic';
    case FORM = 'form';
    case DECISION = 'decision';
    case NOTIFICATION = 'notification';
}