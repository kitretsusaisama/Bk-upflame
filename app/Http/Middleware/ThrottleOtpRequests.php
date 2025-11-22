<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ThrottleOtpRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Create a unique key based on recipient (email/phone) and IP
        $key = $this->throttleKey($request);
        
        // Allow 5 OTP requests per hour per recipient+IP combination
        $maxAttempts = config('auth.otp.rate_limit_attempts', 5);
        $decayMinutes = config('auth.otp.rate_limit_decay', 60);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TOO_MANY_REQUESTS',
                    'message' => 'Too many OTP requests. Please try again later.'
                ]
            ], 429);
        }

        // Increment the attempt count
        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Generate the rate limiting key for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        // Normalize the recipient (email or phone)
        $recipient = strtolower($request->input('email', $request->input('phone', '')));
        
        return Str::transliterate(Str::lower($recipient).'|'.$request->ip());
    }
}