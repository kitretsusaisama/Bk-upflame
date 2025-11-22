<?php

namespace App\Http\Controllers\Api\V1\Workflow;

use App\Http\Controllers\Controller;
use App\Domains\Workflow\Models\Workflow;
use App\Http\Resources\WorkflowResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // Scope the workflow listing to the authenticated tenant
        $workflows = Workflow::where('tenant_id', $request->user()->tenant_id)->paginate(15);
        
        return WorkflowResource::collection($workflows);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\WorkflowResource
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            // Add other validation rules as needed
        ]);
        
        // Add tenant_id from the authenticated user
        $data = $validatedData;
        $data['tenant_id'] = $request->user()->tenant_id;
        
        $workflow = Workflow::create($data);
        
        return new WorkflowResource($workflow);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domains\Workflow\Models\Workflow  $workflow
     * @return \App\Http\Resources\WorkflowResource
     */
    public function show(Workflow $workflow)
    {
        return new WorkflowResource($workflow);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domains\Workflow\Models\Workflow  $workflow
     * @return \App\Http\Resources\WorkflowResource
     */
    public function update(Request $request, Workflow $workflow)
    {
        $updateData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string',
            // Add other validation rules as needed
        ]);
        
        $workflow->update($updateData);
        
        return new WorkflowResource($workflow);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domains\Workflow\Models\Workflow  $workflow
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Workflow $workflow)
    {
        $workflow->delete();
        
        return response()->json([
            'message' => 'Workflow deleted successfully'
        ]);
    }
}