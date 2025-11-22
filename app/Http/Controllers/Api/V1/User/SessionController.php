<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Get the current user's active sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $sessions = $request->user()->sessions()->get();
        
        return response()->json([
            'sessions' => $sessions
        ]);
    }

    /**
     * Logout from a specific session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $sessionId): JsonResponse
    {
        $session = $request->user()->sessions()->where('session_id', $sessionId)->first();
        
        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }
        
        $session->delete();
        
        return response()->json([
            'message' => 'Session logged out successfully'
        ]);
    }
}