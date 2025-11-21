<?php

namespace App\Domains\Notification\Repositories;

use App\Domains\Notification\Models\Notification;
use App\Support\BaseRepository;

class NotificationRepository extends BaseRepository
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByRecipient($recipientUserId, $limit = 20)
    {
        return $this->model->where('recipient_user_id', $recipientUserId)->paginate($limit);
    }

    public function findByStatus($status, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function findPending($limit = 100)
    {
        return $this->model->where('status', 'pending')
            ->whereNull('scheduled_at')
            ->orWhere('scheduled_at', '<=', now())
            ->paginate($limit);
    }
}
