<?php

namespace App\Domains\Provider\Services;

use App\Domains\Provider\Repositories\ProviderDocumentRepository;
use Illuminate\Support\Str;

class ProviderDocumentService
{
    protected $providerDocumentRepository;

    public function __construct(ProviderDocumentRepository $providerDocumentRepository)
    {
        $this->providerDocumentRepository = $providerDocumentRepository;
    }

    public function uploadDocument($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        return $this->providerDocumentRepository->create($data);
    }

    public function verifyDocument($documentId, $verifiedBy)
    {
        return $this->providerDocumentRepository->update($documentId, [
            'status' => 'verified',
            'verified_by' => $verifiedBy,
            'verified_at' => now()
        ]);
    }

    public function rejectDocument($documentId, $reason, $verifiedBy)
    {
        return $this->providerDocumentRepository->update($documentId, [
            'status' => 'rejected',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    public function getDocumentsByProvider($providerId, $limit = 20)
    {
        return $this->providerDocumentRepository->findByProvider($providerId, $limit);
    }
}
