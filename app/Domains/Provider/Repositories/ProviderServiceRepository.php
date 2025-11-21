<?php

namespace App\Domains\Provider\Repositories;

use App\Domains\Provider\Models\ProviderService;
use App\Support\BaseRepository;

class ProviderServiceRepository extends BaseRepository
{
    public function __construct(ProviderService $model)
    {
        parent::__construct($model);
    }

    public function findByProvider($providerId, $limit = 20)
    {
        return $this->model->where('provider_id', $providerId)->paginate($limit);
    }

    public function findActiveByProvider($providerId, $limit = 20)
    {
        return $this->model->where('provider_id', $providerId)
            ->where('is_active', true)
            ->paginate($limit);
    }
}
