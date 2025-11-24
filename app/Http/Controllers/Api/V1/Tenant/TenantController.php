<?php

namespace App\Http\Controllers\Api\V1\Tenant;

use App\Http\Controllers\Controller;
use App\Domains\Tenant\Models\Tenant;
use App\Http\Resources\TenantResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $tenants = Tenant::paginate(15);
        
        return TenantResource::collection($tenants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TenantResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants',
            'domain' => 'required|string|unique:tenants',
            // Add other validation rules as needed
        ]);
        
        $tenant = Tenant::create($data);
        
        return new TenantResource($tenant);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domains\Tenant\Models\Tenant  $tenant
     * @return \App\Http\Resources\TenantResource
     */
    public function show(Tenant $tenant)
    {
        return new TenantResource($tenant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domains\Tenant\Models\Tenant  $tenant
     * @return \App\Http\Resources\TenantResource
     */
    public function update(Request $request, Tenant $tenant)
    {
        $updateData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:tenants,slug,' . $tenant->id,
            'domain' => 'sometimes|string|unique:tenants,domain,' . $tenant->id,
            // Add other validation rules as needed
        ]);
        
        $tenant->update($updateData);
        
        return new TenantResource($tenant);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domains\Tenant\Models\Tenant  $tenant
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        
        return response()->json([
            'message' => 'Tenant deleted successfully'
        ]);
    }
}