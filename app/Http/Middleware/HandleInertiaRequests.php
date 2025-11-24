<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domains\Menu\Services\MenuService;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();

        return array_merge(parent::share($request), [
            'appName' => config('app.name'),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->profile?->full_name ?? $user->name,
                ] : null,
                'permissions' => $user?->getAllPermissions()->pluck('name') ?? [],
                'roles' => $user?->roles->pluck('name') ?? [],
            ],
            'tenant' => function() use ($request) {
                $tenant = app('tenant'); // if you already bind tenant
                return $tenant ? $tenant->only(['id','name','domain']) : null;
            },
            'menu' => function() use ($request) {
                // MenuService returns pre-filtered hierarchical menu for user+tenant
                $menuService = app(MenuService::class);
                try {
                    $menuCollection = $menuService->getSidebarForCurrentUser();
                    return $menuCollection->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            },
            'flash' => [
                'success' => fn() => session('success'),
                'error' => fn() => session('error'),
            ],
        ]);
    }
}