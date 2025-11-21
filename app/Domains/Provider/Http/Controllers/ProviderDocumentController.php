<?php

namespace App\Domains\Provider\Http\Controllers;

use App\Domains\Provider\Services\ProviderDocumentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProviderDocumentController extends Controller
{
    protected $providerDocumentService;

    public function __construct(ProviderDocumentService $providerDocumentService)
    {
        $this->providerDocumentService = $providerDocumentService;
    }

    public function index($providerId, Request $request)
    {
        $documents = $this->providerDocumentService->getDocumentsByProvider($providerId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $documents
        ]);
    }

    public function store($providerId, Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'file_id' => 'required|string',
            'file_url' => 'nullable|string|max:500'
        ]);

        $validated['provider_id'] = $providerId;
        
        $document = $this->providerDocumentService->uploadDocument($validated);

        return response()->json([
            'status' => 'success',
            'data' => $document
        ], 201);
    }

    public function verify($providerId, $id, Request $request)
    {
        $userId = $request->user()->id;
        
        $document = $this->providerDocumentService->verifyDocument($id, $userId);

        return response()->json([
            'status' => 'success',
            'data' => $document
        ]);
    }

    public function reject($providerId, $id, Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string'
        ]);

        $userId = $request->user()->id;
        
        $document = $this->providerDocumentService->rejectDocument($id, $validated['reason'], $userId);

        return response()->json([
            'status' => 'success',
            'data' => $document
        ]);
    }
}
