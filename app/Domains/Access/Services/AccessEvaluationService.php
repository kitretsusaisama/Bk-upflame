<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Repositories\PolicyRepository;
use App\Domains\Identity\Models\User;

class AccessEvaluationService
{
    protected $policyRepository;

    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function evaluateAccess(User $user, $resource, $action, $context = [])
    {
        $tenantId = $user->tenant_id;
        
        $permissions = $user->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
        
        $permissionName = $resource . '.' . $action;
        $hasPermission = $permissions->contains('name', $permissionName);
        
        if (!$hasPermission) {
            return [
                'decision' => 'deny',
                'reason' => 'No permission found for ' . $permissionName
            ];
        }
        
        $policies = $this->policyRepository->findEnabledByTenant($tenantId, 100);
        
        foreach ($policies as $policy) {
            $target = json_decode($policy->target_json, true);
            $rules = json_decode($policy->rules_json, true);
            
            if ($this->matchesTarget($target, $resource, $action)) {
                $result = $this->evaluateRules($rules, $user, $context);
                
                if ($result === false) {
                    return [
                        'decision' => 'deny',
                        'policy_id' => $policy->id,
                        'reason' => 'Policy ' . $policy->name . ' denied access'
                    ];
                }
            }
        }
        
        return [
            'decision' => 'allow',
            'reason' => 'Permission granted and no policies deny access'
        ];
    }

    protected function matchesTarget($target, $resource, $action)
    {
        if (!isset($target['resource']) || !isset($target['action'])) {
            return false;
        }
        
        return $target['resource'] === $resource && $target['action'] === $action;
    }

    protected function evaluateRules($rules, User $user, $context)
    {
        if (!isset($rules['conditions']) || !is_array($rules['conditions'])) {
            return true;
        }
        
        foreach ($rules['conditions'] as $condition) {
            if (!$this->evaluateCondition($condition, $user, $context)) {
                return false;
            }
        }
        
        return true;
    }

    protected function evaluateCondition($condition, User $user, $context)
    {
        foreach ($condition as $key => $value) {
            if (str_starts_with($key, 'user.')) {
                $attribute = str_replace('user.', '', $key);
                
                if ($attribute === 'role') {
                    $hasRole = $user->roles->contains('name', $value);
                    if (!$hasRole) {
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
}
