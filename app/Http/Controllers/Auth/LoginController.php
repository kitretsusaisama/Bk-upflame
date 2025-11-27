<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Identity\Services\AuthenticationService;
use App\Http\Controllers\Controller;
use App\Services\SessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected AuthenticationService $authService;
    protected SessionManager $sessionManager;

    public function __construct(
        AuthenticationService $authService,
        SessionManager $sessionManager
    ) {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
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
            // Check if user is active before proceeding
            $user = Auth::user();
            
            if (!$user->isActive()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is not active.',
                ]);
            }

            // Regenerate session ID to prevent session fixation
            $request->session()->regenerate();

            // Initialize last activity tracking
            session(['last_activity_time' => time()]);
            session(['last_session_regeneration' => time()]);

            // Check session limit
            if ($this->sessionManager->hasReachedSessionLimit($user)) {
                // Enforce limit by removing oldest session
                $this->sessionManager->enforceSessionLimit($user, session()->getId());
            }

            // Create session record
            $this->sessionManager->createSession($user, $request, session()->getId());

            // Update last login
            $user->update(['last_login' => now()]);

            // Issue SSO token
            $this->issueSsoToken($request, $user);

            // Redirect to unified dashboard
            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Issue SSO token for cross-app authentication
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domains\Identity\Models\User  $user
     * @return void
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
        $sessionId = session()->getId();

        if ($user) {
            // Revoke SSO token
            $this->authService->revokeTokenByName($user, 'web-session');

            // Check if user wants to logout from all devices
            if ($request->input('logout_all')) {
                $this->sessionManager->terminateAllSessions($user);
            } else {
                // Just terminate current session
                $this->sessionManager->terminateSession($sessionId);
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('sso_token');

        cookie()->queue(cookie()->forget('sso_token'));

        return redirect()->route('login');
    }
}