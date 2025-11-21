<?php

namespace App\Domains\Booking\Repositories;

use App\Domains\Booking\Models\Service;
use App\Support\BaseRepository;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findActiveByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->paginate($limit);
    }

    public function findByCategory($category, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('category', $category)
            ->where('is_active', true);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }
}
