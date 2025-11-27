<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ImpersonationService;
use Illuminate\Support\Facades\View;

class CheckImpersonation
{
    protected ImpersonationService $impersonationService;

    public function __construct(ImpersonationService $impersonationService)
    {
        $this->impersonationService = $impersonationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->impersonationService->isImpersonating()) {
            $impersonator = $this->impersonationService->getImpersonator();
            View::share('impersonating', true);
            View::share('impersonator', $impersonator);
        } else {
            View::share('impersonating', false);
        }

        return $next($request);
    }
}
