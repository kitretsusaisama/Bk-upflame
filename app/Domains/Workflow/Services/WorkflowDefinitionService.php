<?php

namespace App\Domains\Workflow\Services;

use App\Domains\Workflow\Repositories\WorkflowDefinitionRepository;
use Illuminate\Support\Str;

class WorkflowDefinitionService
{
    protected $workflowDefinitionRepository;

    public function __construct(WorkflowDefinitionRepository $workflowDefinitionRepository)
    {
        $this->workflowDefinitionRepository = $workflowDefinitionRepository;
    }

    public function createDefinition($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        if (isset($data['steps']) && is_array($data['steps'])) {
            $data['steps_json'] = json_encode($data['steps']);
            unset($data['steps']);
        }
        
        return $this->workflowDefinitionRepository->create($data);
    }

    public function updateDefinition($id, $data)
    {
        if (isset($data['steps']) && is_array($data['steps'])) {
            $data['steps_json'] = json_encode($data['steps']);
            unset($data['steps']);
        }
        
        return $this->workflowDefinitionRepository->update($id, $data);
    }

    public function deleteDefinition($id)
    {
        return $this->workflowDefinitionRepository->delete($id);
    }

    public function getDefinitionById($id)
    {
        return $this->workflowDefinitionRepository->findById($id);
    }

    public function getDefinitionsByTenant($tenantId, $limit = 20)
    {
        return $this->workflowDefinitionRepository->findByTenant($tenantId, $limit);
    }

    public function getDefinitionsByRoleFamily($roleFamily, $tenantId, $limit = 20)
    {
        return $this->workflowDefinitionRepository->findByRoleFamily($roleFamily, $tenantId, $limit);
    }
}
