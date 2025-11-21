<?php

namespace App\Domains\Identity\Repositories;

use App\Support\BaseRepository;
use App\Domains\Identity\Models\Tenant;

class TenantRepository extends BaseRepository
{
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }

    public function findByDomain(string $domain)
    {
        return $this->model->where('domain', $domain)->first();
    }

    public function findByDomainThroughMapping(string $domain)
    {
        return $this->model->whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain)
                  ->where('is_verified', true);
        })->first();
    }

    public function getActiveTenants(int $limit = 20)
    {
        return $this->model->where('status', 'active')->paginate($limit);
    }
}