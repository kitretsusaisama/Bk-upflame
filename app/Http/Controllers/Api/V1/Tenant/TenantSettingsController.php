<?php

namespace App\Http\Controllers\Api\V1\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    /**
     * Get the current tenant's settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $tenantId = $request->attributes->get('tenant_id');
        
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }
        
        $tenant = \App\Domains\Tenant\Models\Tenant::find($tenantId);
        
        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }
        
        return response()->json([
            'settings' => $tenant->settings
        ]);
    }

    /**
     * Update the current tenant's settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $tenantId = $request->attributes->get('tenant_id');
        
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }
        
        $tenant = \App\Domains\Tenant\Models\Tenant::find($tenantId);
        
        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }
        
        $validatedData = $request->validate([
            'settings' => 'required|array',
            // Add specific validation rules for settings as needed
        ]);
        
        $tenant->update(['settings' => $validatedData['settings']]);
        
        return response()->json([
            'message' => 'Tenant settings updated successfully',
            'settings' => $tenant->settings
        ]);
    }
}