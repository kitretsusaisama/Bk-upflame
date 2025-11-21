<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
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

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if user is active
            if (!Auth::user()->isActive()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is not active.',
                ]);
            }

            // Update last login
            Auth::user()->update(['last_login' => now()]);

            // Redirect based on user role
            return $this->redirectBasedOnRole(Auth::user());
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
    protected function redirectBasedOnRole($user)
    {
        // Check if user has super admin role
        if ($user->hasRole('super_admin')) {
            return redirect()->intended(route('superadmin.dashboard'));
        }
        
        // Check if user has tenant admin role
        if ($user->hasRole('tenant_admin')) {
            return redirect()->intended(route('tenantadmin.dashboard'));
        }
        
        // Check if user has provider role
        if ($user->hasRole('provider')) {
            return redirect()->intended(route('provider.dashboard'));
        }
        
        // Check if user has ops role
        if ($user->hasRole('ops')) {
            return redirect()->intended(route('ops.dashboard'));
        }
        
        // Default to customer dashboard
        return redirect()->intended(route('customer.dashboard'));
    }

    /**
     * Log the user out of the application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}