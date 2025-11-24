<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // Debug CSRF token verification
        \Log::info('CSRF Token Check', [
            'uri' => $request->getRequestUri(),
            'method' => $request->getMethod(),
            'token' => $request->header('X-CSRF-TOKEN'),
            'session_token' => $request->session()->token() ?? 'no session token',
            'except' => $this->except
        ]);

        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            \Log::error('CSRF Token Mismatch', [
                'uri' => $request->getRequestUri(),
                'method' => $request->getMethod(),
                'token' => $request->header('X-CSRF-TOKEN'),
                'session_token' => $request->session()->token() ?? 'no session token'
            ]);
            
            throw $e;
        }
    }
}