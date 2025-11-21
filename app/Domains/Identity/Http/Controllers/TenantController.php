<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $tenants = $this->tenantService->getAllTenants($request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $tenants
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8'
        ]);

        $tenant = $this->tenantService->createTenantWithAdmin($validated);

        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ], 201);
    }

    public function show($id)
    {
        $tenant = $this->tenantService->getTenantById($id);

        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'sometimes|string|max:255|unique:tenants,domain,' . $id,
            'status' => 'sometimes|in:active,inactive,suspended'
        ]);

        $tenant = $this->tenantService->updateTenant($id, $validated);

        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ]);
    }

    public function addDomain(Request $request, $id)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:tenant_domains,domain',
            'is_primary' => 'nullable|boolean'
        ]);

        $domain = $this->tenantService->addDomain($id, $validated);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Domain added successfully',
                'domain' => $domain
            ]
        ]);
    }
}
