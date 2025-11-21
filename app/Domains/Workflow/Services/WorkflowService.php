<?php

namespace App\Domains\Workflow\Services;

use App\Domains\Workflow\Repositories\WorkflowRepository;
use App\Domains\Workflow\Repositories\WorkflowDefinitionRepository;
use App\Domains\Workflow\Repositories\WorkflowStepRepository;
use Illuminate\Support\Str;

class WorkflowService
{
    protected $workflowRepository;
    protected $workflowDefinitionRepository;
    protected $workflowStepRepository;

    public function __construct(
        WorkflowRepository $workflowRepository,
        WorkflowDefinitionRepository $workflowDefinitionRepository,
        WorkflowStepRepository $workflowStepRepository
    ) {
        $this->workflowRepository = $workflowRepository;
        $this->workflowDefinitionRepository = $workflowDefinitionRepository;
        $this->workflowStepRepository = $workflowStepRepository;
    }

    public function createWorkflow($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        $definition = $this->workflowDefinitionRepository->findById($data['definition_id']);
        
        if (!$definition) {
            throw new \Exception('Workflow definition not found');
        }
        
        $stepsDefinition = json_decode($definition->steps_json, true);
        
        if (is_array($stepsDefinition) && count($stepsDefinition) > 0) {
            $data['current_step_key'] = $stepsDefinition[0]['key'] ?? null;
        }
        
        $workflow = $this->workflowRepository->create($data);
        
        if (is_array($stepsDefinition)) {
            foreach ($stepsDefinition as $stepDef) {
                $this->workflowStepRepository->create([
                    'id' => Str::uuid()->toString(),
                    'workflow_id' => $workflow->id,
                    'step_key' => $stepDef['key'],
                    'title' => $stepDef['title'],
                    'description' => $stepDef['description'] ?? null,
                    'step_type' => $stepDef['type'],
                    'config_json' => isset($stepDef['config']) ? json_encode($stepDef['config']) : null,
                    'status' => 'pending'
                ]);
            }
        }
        
        return $workflow->load('steps');
    }

    public function getWorkflowById($id)
    {
        $workflow = $this->workflowRepository->findById($id);
        return $workflow ? $workflow->load(['steps', 'definition']) : null;
    }

    public function getWorkflowsByTenant($tenantId, $limit = 20)
    {
        return $this->workflowRepository->findByTenant($tenantId, $limit);
    }

    public function submitStep($workflowId, $stepKey, $data)
    {
        $workflow = $this->workflowRepository->findById($workflowId);
        
        if (!$workflow) {
            throw new \Exception('Workflow not found');
        }
        
        $step = $this->workflowStepRepository->findByStepKey($workflowId, $stepKey);
        
        if (!$step) {
            throw new \Exception('Step not found');
        }
        
        $this->workflowStepRepository->update($step->id, [
            'data_json' => json_encode($data),
            'status' => 'submitted',
            'attempted_at' => now(),
            'completed_at' => now()
        ]);
        
        $definition = json_decode($workflow->definition->steps_json, true);
        $currentIndex = array_search($stepKey, array_column($definition, 'key'));
        
        if ($currentIndex !== false && isset($definition[$currentIndex + 1])) {
            $nextStepKey = $definition[$currentIndex + 1]['key'];
            $this->workflowRepository->update($workflowId, [
                'current_step_key' => $nextStepKey,
                'status' => 'in_progress'
            ]);
            
            return ['next_step_key' => $nextStepKey];
        } else {
            $this->workflowRepository->update($workflowId, [
                'current_step_key' => null,
                'status' => 'submitted'
            ]);
            
            return ['next_step_key' => null, 'workflow_completed' => true];
        }
    }

    public function approveWorkflow($workflowId)
    {
        $workflow = $this->workflowRepository->update($workflowId, [
            'status' => 'approved'
        ]);
        
        return $workflow;
    }

    public function rejectWorkflow($workflowId, $reason = null)
    {
        $workflow = $this->workflowRepository->update($workflowId, [
            'status' => 'rejected'
        ]);
        
        return $workflow;
    }
}
