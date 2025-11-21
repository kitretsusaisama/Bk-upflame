<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Services\AuthenticationService;
use App\Support\Concerns\DeterminesDashboardRoute;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SsoController extends Controller
{
    use DeterminesDashboardRoute;

    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function token(Request $request)
    {
        $user = $request->user()->loadMissing('roles');

        $token = Session::get('sso_token') ?? $this->authService->createSsoToken($user);
        Session::put('sso_token', $token);

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'redirect_to' => route($this->determineDashboardRoute($user)),
            ],
        ]);
    }

    public function exchange(Request $request)
    {
        $user = $request->user()->loadMissing('roles');

        if (!Session::isStarted()) {
            Session::start();
        }

        Session::migrate(true);

        Auth::guard('web')->login($user);

        $token = $this->authService->createSsoToken($user);
        Session::put('sso_token', $token);

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

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'redirect_to' => route($this->determineDashboardRoute($user)),
            ],
        ]);
    }

    public function revoke(Request $request)
    {
        $user = $request->user();
        $this->authService->revokeTokenByName($user, 'web-session');

        Session::forget('sso_token');
        cookie()->queue(cookie()->forget('sso_token'));

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'SSO session revoked',
            ],
        ]);
    }
}

