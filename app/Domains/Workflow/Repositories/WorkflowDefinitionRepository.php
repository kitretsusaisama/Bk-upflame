<?php

namespace App\Domains\Workflow\Repositories;

use App\Domains\Workflow\Models\WorkflowDefinition;
use App\Support\BaseRepository;

class WorkflowDefinitionRepository extends BaseRepository
{
    public function __construct(WorkflowDefinition $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByRoleFamily($roleFamily, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('role_family', $roleFamily)
            ->where('is_active', true);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function findActiveByName($name, $tenantId)
    {
        return $this->model->where('name', $name)
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->first();
    }
}
