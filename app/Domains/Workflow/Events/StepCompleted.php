<?php

namespace App\Domains\Workflow\Events;

use App\Domains\Workflow\Models\WorkflowStepInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StepCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public WorkflowStepInstance $stepInstance;

    public function __construct(WorkflowStepInstance $stepInstance)
    {
        $this->stepInstance = $stepInstance;
    }
}