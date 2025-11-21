<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Identity\Services\AuthenticationService;
use App\Http\Controllers\Controller;
use App\Support\Concerns\DeterminesDashboardRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use DeterminesDashboardRoute;

    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Always enable "remember me" functionality
        $remember = true;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is active
            if (!Auth::user()->isActive()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is not active.',
                ]);
            }

            $user = Auth::user();
            $user->update(['last_login' => now()]);

            $this->issueSsoToken($request, $user);

            return redirect()->route($this->determineDashboardRoute($user));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Redirect user based on their role
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function issueSsoToken(Request $request, $user): void
    {
        $token = $this->authService->createSsoToken($user);

        $request->session()->put('sso_token', $token);

        cookie()->queue(cookie(
            'sso_token',
            $token,
            60 * 24,
            '/',
            null,
            config('session.secure', false),
            true,
            false,
            config('session.same_site') ?? 'lax'
        ));

        Log::info('Issued SSO token for user', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /**
     * Log the user out of the application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $this->authService->revokeTokenByName($user, 'web-session');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('sso_token');

        cookie()->queue(cookie()->forget('sso_token'));

        return redirect()->route('login');
    }
}