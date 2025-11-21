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

    public function totalForUser(string $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function countByStatusForUser(string $userId, string $status): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', $status)
            ->count();
    }

    public function totalAmountForUser(string $userId): float
    {
        return (float) $this->model->where('user_id', $userId)->sum('amount');
    }

    public function totalForProvider(string $providerId): int
    {
        return $this->model->where('provider_id', $providerId)->count();
    }

    public function countByStatusForProvider(string $providerId, string $status): int
    {
        return $this->model
            ->where('provider_id', $providerId)
            ->where('status', $status)
            ->count();
    }

    public function upcomingForUser(string $userId, int $limit = 10)
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereIn('status', ['processing', 'confirmed'])
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at')
            ->with(['service', 'provider'])
            ->limit($limit)
            ->get();
    }

    public function upcomingForProvider(string $providerId, int $limit = 10)
    {
        return $this->model
            ->where('provider_id', $providerId)
            ->whereIn('status', ['processing', 'confirmed'])
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at')
            ->with(['user.profile', 'service'])
            ->limit($limit)
            ->get();
    }
}
