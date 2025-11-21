<?php

namespace App\Domains\Notification\Repositories;

use App\Domains\Notification\Models\NotificationTemplate;
use App\Support\BaseRepository;

class NotificationTemplateRepository extends BaseRepository
{
    public function __construct(NotificationTemplate $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByName($name, $tenantId)
    {
        return $this->model->where('name', $name)
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->first();
    }

    public function findByChannel($channel, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('channel', $channel)
            ->where('is_active', true);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }
}
