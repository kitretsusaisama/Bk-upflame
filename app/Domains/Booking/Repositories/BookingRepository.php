<?php

namespace App\Domains\Booking\Repositories;

use App\Domains\Booking\Models\Booking;
use App\Support\BaseRepository;

class BookingRepository extends BaseRepository
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByUser($userId, $limit = 20)
    {
        return $this->model->where('user_id', $userId)->paginate($limit);
    }

    public function findByProvider($providerId, $limit = 20)
    {
        return $this->model->where('provider_id', $providerId)->paginate($limit);
    }

    public function findByStatus($status, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function findByReference($reference)
    {
        return $this->model->where('booking_reference', $reference)->first();
    }
}
