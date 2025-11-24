<?php

namespace App\Domains\Workflow\Events;

use App\Domains\Workflow\Models\WorkflowFormSubmission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public WorkflowFormSubmission $submission;

    public function __construct(WorkflowFormSubmission $submission)
    {
        $this->submission = $submission;
    }
}