<?php

namespace App\Http\Controllers;

use App\Services\SessionManager;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * List all active sessions for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $sessions = $this->sessionManager->getActiveSessions($user);

        // Map sessions to friendly format
        $sessionData = $sessions->map(function ($session) use ($request) {
            return [
                'id' => $session->id,
                'device' => $this->parseUserAgent($session->user_agent),
                'ip_address' => $session->ip_address,
                'last_activity' => $session->last_activity?->diffForHumans(),
                'is_current' => $session->session_id === session()->getId(),
                'created_at' => $session->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $sessionData,
            ]);
        }

        return view('dashboard.sessions', [
            'sessions' => $sessionData,
        ]);
    }

    /**
     * Destroy a specific session
     *
     * @param Request $request
     * @param string $sessionId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $sessionId)
    {
        $user = $request->user();
        
        // Verify session belongs to user
        $session = $user->sessions()->findOrFail($sessionId);
        
        $this->sessionManager->terminateSession($session->session_id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Session terminated successfully.',
            ]);
        }

        return redirect()->route('sessions.index')
            ->with('success', 'Session terminated successfully.');
    }

    /**
     * Logout from all devices
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroyAll(Request $request)
    {
        $user = $request->user();
        $currentSessionId = session()->getId();
        
        // Terminate all sessions except current one
        $count = $this->sessionManager->terminateAllSessions($user, $currentSessionId);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully logged out from {$count} other device(s).",
                'count' => $count,
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', "Successfully logged out from {$count} other device(s).");
    }

    /**
     * Parse user agent to human-readable device info
     *
     * @param string|null $userAgent
     * @return string
     */
    protected function parseUserAgent(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        // Simple parsing - you can use a library like jenssegers/agent for better parsing
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome Browser';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox Browser';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari Browser';
        } elseif (str_contains($userAgent, 'Edge')) {
            return 'Edge Browser';
        }

        return 'Unknown Browser';
    }
}
