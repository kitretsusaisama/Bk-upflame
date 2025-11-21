<?php

namespace App\Domains\Provider\Repositories;

use App\Domains\Provider\Models\ProviderDocument;
use App\Support\BaseRepository;

class ProviderDocumentRepository extends BaseRepository
{
    public function __construct(ProviderDocument $model)
    {
        parent::__construct($model);
    }

    public function findByProvider($providerId, $limit = 20)
    {
        return $this->model->where('provider_id', $providerId)->paginate($limit);
    }

    public function findByStatus($status, $providerId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($providerId) {
            $query->where('provider_id', $providerId);
        }
        
        return $query->paginate($limit);
    }
}
