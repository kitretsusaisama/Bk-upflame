<?php

namespace App\Http\Controllers;

use App\Domains\Workflow\Repositories\WorkflowRepository;
use Illuminate\Http\Request;

class OpsDashboardController extends Controller
{
    protected $workflowRepository;

    public function __construct(WorkflowRepository $workflowRepository)
    {
        $this->workflowRepository = $workflowRepository;
    }

    public function dashboard()
    {
        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id;

        $menuItems = [
            ['label' => 'Dashboard', 'route' => 'ops.dashboard', 'icon' => '📊'],
            ['label' => 'Workflows', 'route' => 'ops.workflows', 'icon' => '📋', 'badge' => '8'],
            ['label' => 'Approvals', 'route' => 'ops.approvals', 'icon' => '✅'],
            ['label' => 'Reports', 'route' => 'ops.reports', 'icon' => '📈'],
            ['separator' => true, 'label' => 'Tools'],
            ['label' => 'Analytics', 'route' => 'ops.analytics', 'icon' => '📊'],
            ['label' => 'Audit Logs', 'route' => 'ops.logs', 'icon' => '📜'],
        ];

        $stats = [
            'active_workflows' => $this->workflowRepository->findByStatus('in_progress', $tenantId, 1)->total(),
            'pending_approvals' => $this->workflowRepository->findByStatus('pending', $tenantId, 1)->total(),
            'completed_today' => $this->workflowRepository->findByStatus('approved', $tenantId, 1)->total(),
            'avg_completion_time' => '2.5',
        ];

        $activeWorkflows = $this->workflowRepository->findByTenant($tenantId, 10);

        return view('ops.dashboard', compact('menuItems', 'stats', 'activeWorkflows'));
    }
}