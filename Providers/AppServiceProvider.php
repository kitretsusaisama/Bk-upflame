<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Domains\Identity\Repositories\UserRepository::class);
        $this->app->singleton(\App\Domains\Identity\Repositories\TenantRepository::class);
        $this->app->singleton(\App\Domains\Identity\Services\AuthenticationService::class);
        $this->app->singleton(\App\Domains\Identity\Services\TenantService::class);
        
        $this->app->singleton(\App\Domains\Access\Repositories\RoleRepository::class);
        $this->app->singleton(\App\Domains\Access\Repositories\PermissionRepository::class);
        $this->app->singleton(\App\Domains\Access\Repositories\PolicyRepository::class);
        $this->app->singleton(\App\Domains\Access\Repositories\PolicyAssignmentRepository::class);
        $this->app->singleton(\App\Domains\Access\Services\RoleService::class);
        $this->app->singleton(\App\Domains\Access\Services\PermissionService::class);
        $this->app->singleton(\App\Domains\Access\Services\PolicyService::class);
        $this->app->singleton(\App\Domains\Access\Services\AccessEvaluationService::class);
        
        $this->app->singleton(\App\Domains\Workflow\Repositories\WorkflowRepository::class);
        $this->app->singleton(\App\Domains\Workflow\Repositories\WorkflowDefinitionRepository::class);
        $this->app->singleton(\App\Domains\Workflow\Repositories\WorkflowStepRepository::class);
        $this->app->singleton(\App\Domains\Workflow\Services\WorkflowService::class);
        $this->app->singleton(\App\Domains\Workflow\Services\WorkflowDefinitionService::class);
        
        $this->app->singleton(\App\Domains\Provider\Repositories\ProviderRepository::class);
        $this->app->singleton(\App\Domains\Provider\Repositories\ProviderDocumentRepository::class);
        $this->app->singleton(\App\Domains\Provider\Repositories\ProviderServiceRepository::class);
        $this->app->singleton(\App\Domains\Provider\Services\ProviderService::class);
        $this->app->singleton(\App\Domains\Provider\Services\ProviderDocumentService::class);
        
        $this->app->singleton(\App\Domains\Booking\Repositories\BookingRepository::class);
        $this->app->singleton(\App\Domains\Booking\Repositories\ServiceRepository::class);
        $this->app->singleton(\App\Domains\Booking\Services\BookingService::class);
        $this->app->singleton(\App\Domains\Booking\Services\ServiceService::class);
        
        $this->app->singleton(\App\Domains\Notification\Repositories\NotificationRepository::class);
        $this->app->singleton(\App\Domains\Notification\Repositories\NotificationTemplateRepository::class);
        $this->app->singleton(\App\Domains\Notification\Services\NotificationService::class);
    }

    public function boot(): void
    {
        Route::aliasMiddleware('tenant.resolution', \App\Interfaces\Http\Middleware\TenantResolution::class);
        Route::aliasMiddleware('tenant.scope', \App\Interfaces\Http\Middleware\TenantScope::class);

        if (file_exists(base_path('routes/api.php'))) {
            Route::middleware('api')
                ->group(base_path('routes/api.php'));
        }
    }
}
