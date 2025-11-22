<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Identity\Contracts\UserRepositoryInterface;
use App\Domains\Identity\Repositories\EloquentUserRepository;
use App\Domains\Tenant\Contracts\TenantRepositoryInterface;
use App\Domains\Tenant\Repositories\EloquentTenantRepository;
use App\Domains\Access\Contracts\RoleRepositoryInterface;
use App\Domains\Access\Repositories\EloquentRoleRepository;
use App\Domains\Access\Contracts\PermissionRepositoryInterface;
use App\Domains\Access\Repositories\EloquentPermissionRepository;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Identity Domain
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        
        // Tenant Domain
        $this->app->bind(TenantRepositoryInterface::class, EloquentTenantRepository::class);
        
        // Access Domain
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloquentPermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}