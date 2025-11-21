<?php

namespace App\Support\Concerns;

use App\Domains\Identity\Models\User;

trait DeterminesDashboardRoute
{
    protected array $roleRedirects = [
        'Super Admin' => 'superadmin.dashboard',
        'Tenant Admin' => 'tenantadmin.dashboard',
        'Provider' => 'provider.dashboard',
        'Ops' => 'ops.dashboard',
        'Premium Customer' => 'customer.dashboard',
        'Customer' => 'customer.dashboard',
    ];

    protected function determineDashboardRoute(User $user): string
    {
        $user->loadMissing('roles');

        if ($user->primary_role_id) {
            $primaryRole = $user->roles->firstWhere('id', $user->primary_role_id);
            if ($primaryRole && isset($this->roleRedirects[$primaryRole->name])) {
                return $this->roleRedirects[$primaryRole->name];
            }
        }

        foreach ($this->roleRedirects as $role => $route) {
            if ($user->hasRole($role)) {
                return $route;
            }
        }

        return 'customer.dashboard';
    }
}

