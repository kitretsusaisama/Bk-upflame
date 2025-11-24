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
        // Log CSRF token information (without sensitive data)
        Log::info('CSRF Debug Info', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'has_session_token' => !empty($request->session()->token()),
            'has_request_token' => !empty($request->input('_token') ?? $request->header('X-CSRF-TOKEN')),
            'has_cookies' => !empty($request->cookies->all()),
            'has_session_id' => !empty($request->session()->getId()),
        ]);

        $response = $next($request);

        // Log response status
        Log::info('CSRF Debug Response', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}