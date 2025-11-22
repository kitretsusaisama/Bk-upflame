<?php

namespace App\Http\Controllers\Api\V1\Provider;

use App\Http\Controllers\Controller;
use App\Domain\Provider\Models\Provider;
use App\Http\Resources\ProviderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $providers = Provider::paginate(15);
        
        return ProviderResource::collection($providers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ProviderResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'status' => 'required|string',
            // Add other validation rules as needed
        ]);
        
        $provider = Provider::create($request->validated());
        
        return new ProviderResource($provider);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Provider\Models\Provider  $provider
     * @return \App\Http\Resources\ProviderResource
     */
    public function show(Provider $provider)
    {
        return new ProviderResource($provider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Provider\Models\Provider  $provider
     * @return \App\Http\Resources\ProviderResource
     */
    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'type' => 'sometimes|string',
            'status' => 'sometimes|string',
            // Add other validation rules as needed
        ]);
        
        $provider->update($request->validated());
        
        return new ProviderResource($provider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Provider\Models\Provider  $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        
        return response()->json([
            'message' => 'Provider deleted successfully'
        ]);
    }
}