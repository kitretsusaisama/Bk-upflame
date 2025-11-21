<?php

namespace App\Domains\Provider\Services;

use App\Domains\Provider\Repositories\ProviderRepository;
use App\Domains\Workflow\Services\WorkflowService;
use Illuminate\Support\Str;

class ProviderService
{
    protected $providerRepository;
    protected $workflowService;

    public function __construct(
        ProviderRepository $providerRepository,
        WorkflowService $workflowService
    ) {
        $this->providerRepository = $providerRepository;
        $this->workflowService = $workflowService;
    }

    public function createProvider($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        return $this->providerRepository->create($data);
    }

    public function updateProvider($id, $data)
    {
        return $this->providerRepository->update($id, $data);
    }

    public function deleteProvider($id)
    {
        return $this->providerRepository->delete($id);
    }

    public function getProviderById($id)
    {
        $provider = $this->providerRepository->findById($id);
        return $provider ? $provider->load(['services', 'documents', 'availability']) : null;
    }

    public function getProvidersByTenant($tenantId, $limit = 20)
    {
        return $this->providerRepository->findByTenant($tenantId, $limit);
    }

    public function startOnboarding($providerId, $workflowDefinitionId, $tenantId)
    {
        $workflowData = [
            'definition_id' => $workflowDefinitionId,
            'entity_type' => 'provider',
            'entity_id' => $providerId,
            'tenant_id' => $tenantId
        ];
        
        $workflow = $this->workflowService->createWorkflow($workflowData);
        
        $this->providerRepository->update($providerId, [
            'workflow_id' => $workflow->id
        ]);
        
        return $workflow;
    }
}
