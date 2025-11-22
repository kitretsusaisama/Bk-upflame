<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log CSRF token information
        Log::info('CSRF Debug Info', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_token' => $request->session()->token() ?? 'No session token',
            'request_token' => $request->input('_token') ?? $request->header('X-CSRF-TOKEN') ?? 'No request token',
            'cookies' => $request->cookies->all(),
            'session_id' => $request->session()->getId() ?? 'No session ID',
        ]);

        $response = $next($request);

        // Log response status
        Log::info('CSRF Debug Response', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}