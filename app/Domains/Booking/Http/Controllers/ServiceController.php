<?php

namespace App\Domains\Booking\Http\Controllers;

use App\Domains\Booking\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $services = $this->serviceService->getServicesByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'base_price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'duration_minutes' => 'nullable|integer'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $service = $this->serviceService->createService($validated);

        return response()->json([
            'status' => 'success',
            'data' => $service
        ], 201);
    }

    public function show($id)
    {
        $service = $this->serviceService->getServiceById($id);

        if (!$service) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVICE_NOT_FOUND',
                    'message' => 'Service not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'base_price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'duration_minutes' => 'nullable|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        $service = $this->serviceService->updateService($id, $validated);

        if (!$service) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVICE_NOT_FOUND',
                    'message' => 'Service not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    public function destroy($id)
    {
        $result = $this->serviceService->deleteService($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVICE_NOT_FOUND',
                    'message' => 'Service not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Service deleted successfully'
            ]
        ]);
    }
}
