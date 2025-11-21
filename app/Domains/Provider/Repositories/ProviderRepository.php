<?php

namespace App\Domains\Provider\Repositories;

use App\Domains\Provider\Models\Provider;
use App\Support\BaseRepository;

class ProviderRepository extends BaseRepository
{
    public function __construct(Provider $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByType($providerType, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('provider_type', $providerType);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function findByStatus($status, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function findByUser($userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
}
