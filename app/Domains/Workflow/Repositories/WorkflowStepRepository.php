<?php

namespace App\Domains\Workflow\Repositories;

use App\Domains\Workflow\Models\WorkflowStep;
use App\Support\BaseRepository;

class WorkflowStepRepository extends BaseRepository
{
    public function __construct(WorkflowStep $model)
    {
        parent::__construct($model);
    }

    public function findByWorkflow($workflowId, $limit = 100)
    {
        return $this->model->where('workflow_id', $workflowId)
            ->orderBy('created_at')
            ->paginate($limit);
    }

    public function findByStepKey($workflowId, $stepKey)
    {
        return $this->model->where('workflow_id', $workflowId)
            ->where('step_key', $stepKey)
            ->first();
    }

    public function findByStatus($status, $workflowId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($workflowId) {
            $query->where('workflow_id', $workflowId);
        }
        
        return $query->paginate($limit);
    }
}
