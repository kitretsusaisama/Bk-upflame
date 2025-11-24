<?php

namespace App\Http\Controllers;

use App\Domains\Workflow\Repositories\WorkflowRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OpsDashboardController extends Controller
{
    protected $workflowRepository;
    protected $menuService;

    public function __construct(WorkflowRepository $workflowRepository, MenuService $menuService)
    {
        $this->workflowRepository = $workflowRepository;
        $this->menuService = $menuService;
    }

    public function dashboard()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';

        $stats = [
            'active_workflows' => $this->workflowRepository->findByStatus('in_progress', $tenantId, 1)->total(),
            'pending_approvals' => $this->workflowRepository->findByStatus('pending', $tenantId, 1)->total(),
            'completed_today' => $this->workflowRepository->findByStatus('approved', $tenantId, 1)->total(),
            'avg_completion_time' => '2.5',
        ];

        $activeWorkflows = $this->workflowRepository->findByTenant($tenantId, 10);

        return Inertia::render('Ops/Dashboard', compact('menuItems', 'stats', 'activeWorkflows', 'userRole'));
    }

    public function workflows()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';
        return Inertia::render('Ops/Workflows', compact('menuItems', 'userRole'));
    }

    public function approvals()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';
        return Inertia::render('Ops/Approvals', compact('menuItems', 'userRole'));
    }

    public function reports()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';
        return Inertia::render('Ops/Reports', compact('menuItems', 'userRole'));
    }

    public function analytics()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';
        return Inertia::render('Ops/Analytics', compact('menuItems', 'userRole'));
    }

    public function logs()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Operations';
        return Inertia::render('Ops/Logs', compact('menuItems', 'userRole'));
    }
}