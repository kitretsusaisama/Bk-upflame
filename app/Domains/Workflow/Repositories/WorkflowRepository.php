<?php

namespace App\Domains\Workflow\Repositories;

use App\Domains\Workflow\Models\Workflow;
use App\Support\BaseRepository;

class WorkflowRepository extends BaseRepository
{
    public function __construct(Workflow $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByEntity($entityType, $entityId)
    {
        return $this->model->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->first();
    }

    public function findByStatus($status, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }
}
