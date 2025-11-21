<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Models\PolicyAssignment;
use App\Support\BaseRepository;

class PolicyAssignmentRepository extends BaseRepository
{
    public function __construct(PolicyAssignment $model)
    {
        parent::__construct($model);
    }

    public function findByAssignee($assigneeType, $assigneeId, $limit = 20)
    {
        return $this->model->where('assignee_type', $assigneeType)
            ->where('assignee_id', $assigneeId)
            ->paginate($limit);
    }

    public function findByPolicy($policyId, $limit = 20)
    {
        return $this->model->where('policy_id', $policyId)->paginate($limit);
    }
}
