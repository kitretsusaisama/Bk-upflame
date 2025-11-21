<?php

namespace App\Domains\Booking\Services;

use App\Domains\Booking\Repositories\ServiceRepository;
use Illuminate\Support\Str;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function createService($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        return $this->serviceRepository->create($data);
    }

    public function updateService($id, $data)
    {
        return $this->serviceRepository->update($id, $data);
    }

    public function deleteService($id)
    {
        return $this->serviceRepository->delete($id);
    }

    public function getServiceById($id)
    {
        return $this->serviceRepository->findById($id);
    }

    public function getServicesByTenant($tenantId, $limit = 20)
    {
        return $this->serviceRepository->findActiveByTenant($tenantId, $limit);
    }
}
