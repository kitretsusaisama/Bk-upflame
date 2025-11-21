<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Repositories\PolicyRepository;
use App\Domains\Access\Repositories\PolicyAssignmentRepository;
use Illuminate\Support\Str;

class PolicyService
{
    protected $policyRepository;
    protected $policyAssignmentRepository;

    public function __construct(
        PolicyRepository $policyRepository,
        PolicyAssignmentRepository $policyAssignmentRepository
    ) {
        $this->policyRepository = $policyRepository;
        $this->policyAssignmentRepository = $policyAssignmentRepository;
    }

    public function createPolicy($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        if (isset($data['target'])) {
            $data['target_json'] = json_encode($data['target']);
            unset($data['target']);
        }
        
        if (isset($data['rules'])) {
            $data['rules_json'] = json_encode($data['rules']);
            unset($data['rules']);
        }
        
        return $this->policyRepository->create($data);
    }

    public function updatePolicy($id, $data)
    {
        if (isset($data['target'])) {
            $data['target_json'] = json_encode($data['target']);
            unset($data['target']);
        }
        
        if (isset($data['rules'])) {
            $data['rules_json'] = json_encode($data['rules']);
            unset($data['rules']);
        }
        
        return $this->policyRepository->update($id, $data);
    }

    public function deletePolicy($id)
    {
        return $this->policyRepository->delete($id);
    }

    public function getPolicyById($id)
    {
        return $this->policyRepository->findById($id);
    }

    public function getPoliciesByTenant($tenantId, $limit = 20)
    {
        return $this->policyRepository->findByTenant($tenantId, $limit);
    }

    public function assignPolicy($policyId, $assigneeType, $assigneeId, $tenantId)
    {
        $data = [
            'id' => Str::uuid()->toString(),
            'policy_id' => $policyId,
            'assignee_type' => $assigneeType,
            'assignee_id' => $assigneeId,
            'tenant_id' => $tenantId
        ];
        
        return $this->policyAssignmentRepository->create($data);
    }
}
