<?php

namespace App\Domains\Provider\Http\Controllers;

use App\Domains\Provider\Services\ProviderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProviderController extends Controller
{
    protected $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $providers = $this->providerService->getProvidersByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $providers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'provider_type' => 'required|in:pandit,vendor,partner,astrologer,tutor',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $provider = $this->providerService->createProvider($validated);

        return response()->json([
            'status' => 'success',
            'data' => $provider
        ], 201);
    }

    public function show($id)
    {
        $provider = $this->providerService->getProviderById($id);

        if (!$provider) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PROVIDER_NOT_FOUND',
                    'message' => 'Provider not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $provider
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'provider_type' => 'sometimes|in:pandit,vendor,partner,astrologer,tutor',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'sometimes|in:pending,verified,active,suspended,rejected'
        ]);

        $provider = $this->providerService->updateProvider($id, $validated);

        if (!$provider) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PROVIDER_NOT_FOUND',
                    'message' => 'Provider not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $provider
        ]);
    }

    public function destroy($id)
    {
        $result = $this->providerService->deleteProvider($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PROVIDER_NOT_FOUND',
                    'message' => 'Provider not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Provider deleted successfully'
            ]
        ]);
    }

    public function startOnboarding(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|string',
            'workflow_definition_id' => 'required|string'
        ]);

        $tenantId = app('tenant')->id;
        
        $workflow = $this->providerService->startOnboarding(
            $validated['provider_id'],
            $validated['workflow_definition_id'],
            $tenantId
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'workflow_id' => $workflow->id,
                'message' => 'Onboarding workflow initiated'
            ]
        ]);
    }
}
