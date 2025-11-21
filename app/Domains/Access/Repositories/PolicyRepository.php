<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Models\Policy;
use App\Support\BaseRepository;

class PolicyRepository extends BaseRepository
{
    public function __construct(Policy $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findEnabledByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)
            ->where('is_enabled', true)
            ->orderBy('priority', 'desc')
            ->paginate($limit);
    }

    public function findByName($name, $tenantId = null)
    {
        $query = $this->model->where('name', $name);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->first();
    }
}
