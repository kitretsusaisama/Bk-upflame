<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Identity\Http\Controllers\AuthController;
use App\Domains\Identity\Http\Controllers\TenantController;
use App\Domains\Identity\Http\Controllers\SsoController;
use App\Domains\Identity\Http\Controllers\UserController;
use App\Domains\Access\Http\Controllers\RoleController;
use App\Domains\Access\Http\Controllers\PermissionController;
use App\Domains\Access\Http\Controllers\PolicyController;
use App\Domains\Workflow\Http\Controllers\WorkflowController;
use App\Domains\Workflow\Http\Controllers\WorkflowDefinitionController;
use App\Domains\Provider\Http\Controllers\ProviderController;
use App\Domains\Provider\Http\Controllers\ProviderDocumentController;
use App\Domains\Booking\Http\Controllers\BookingController;
use App\Domains\Booking\Http\Controllers\ServiceController;
use App\Domains\Notification\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\V1\Menu\MenuController;
use App\Http\Controllers\Api\V1\SuperAdmin\UserController as SuperAdminUserController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::post('mfa/setup', [AuthController::class, 'setupMfa']);
            Route::post('mfa/verify', [AuthController::class, 'verifyMfa']);
            Route::post('token', [AuthController::class, 'refreshToken']);
        });
    });

    Route::middleware('auth:sanctum')->prefix('sso')->group(function () {
        Route::post('exchange', [SsoController::class, 'exchange']);
    });

    Route::middleware(['auth:sanctum', 'tenant.resolution', 'tenant.scope'])->group(function () {
        Route::prefix('tenants')->group(function () {
            Route::post('/', [TenantController::class, 'store']);
            Route::get('/{id}', [TenantController::class, 'show']);
            Route::put('/{id}', [TenantController::class, 'update']);
            Route::put('/{id}/domains', [TenantController::class, 'addDomain']);
            Route::get('/', [TenantController::class, 'index']);
        });

        Route::prefix('users')->group(function () {
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::post('/{id}/roles', [UserController::class, 'assignRole']);
            Route::delete('/{id}/roles/{roleId}', [UserController::class, 'removeRole']);
            Route::get('/', [UserController::class, 'index']);
        });

        Route::prefix('roles')->group(function () {
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{id}', [RoleController::class, 'show']);
            Route::put('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
            Route::post('/{id}/permissions', [RoleController::class, 'attachPermission']);
            Route::delete('/{id}/permissions/{permissionId}', [RoleController::class, 'detachPermission']);
            Route::get('/', [RoleController::class, 'index']);
        });

        Route::prefix('permissions')->group(function () {
            Route::post('/', [PermissionController::class, 'store']);
            Route::get('/{id}', [PermissionController::class, 'show']);
            Route::put('/{id}', [PermissionController::class, 'update']);
            Route::delete('/{id}', [PermissionController::class, 'destroy']);
            Route::get('/', [PermissionController::class, 'index']);
        });

        Route::prefix('policies')->group(function () {
            Route::post('/', [PolicyController::class, 'store']);
            Route::get('/{id}', [PolicyController::class, 'show']);
            Route::put('/{id}', [PolicyController::class, 'update']);
            Route::delete('/{id}', [PolicyController::class, 'destroy']);
            Route::post('/{id}/assign', [PolicyController::class, 'assign']);
            Route::get('/', [PolicyController::class, 'index']);
        });

        Route::prefix('workflows')->group(function () {
            Route::post('/', [WorkflowController::class, 'store']);
            Route::get('/{id}', [WorkflowController::class, 'show']);
            Route::put('/{id}', [WorkflowController::class, 'update']);
            Route::delete('/{id}', [WorkflowController::class, 'destroy']);
            Route::post('/{id}/steps/{stepKey}/submit', [WorkflowController::class, 'submitStep']);
            Route::post('/{id}/actions/approve', [WorkflowController::class, 'approve']);
            Route::post('/{id}/actions/reject', [WorkflowController::class, 'reject']);
            Route::get('/', [WorkflowController::class, 'index']);
        });

        Route::prefix('workflow-definitions')->group(function () {
            Route::post('/', [WorkflowDefinitionController::class, 'store']);
            Route::get('/{id}', [WorkflowDefinitionController::class, 'show']);
            Route::put('/{id}', [WorkflowDefinitionController::class, 'update']);
            Route::delete('/{id}', [WorkflowDefinitionController::class, 'destroy']);
            Route::get('/', [WorkflowDefinitionController::class, 'index']);
        });

        Route::prefix('providers')->group(function () {
            Route::post('/', [ProviderController::class, 'store']);
            Route::post('/onboarding', [ProviderController::class, 'startOnboarding']);
            Route::get('/{id}', [ProviderController::class, 'show']);
            Route::put('/{id}', [ProviderController::class, 'update']);
            Route::delete('/{id}', [ProviderController::class, 'destroy']);
            Route::get('/', [ProviderController::class, 'index']);
        });

        Route::prefix('providers/{providerId}/documents')->group(function () {
            Route::post('/', [ProviderDocumentController::class, 'store']);
            Route::get('/{id}', [ProviderDocumentController::class, 'show']);
            Route::put('/{id}/verify', [ProviderDocumentController::class, 'verify']);
            Route::put('/{id}/reject', [ProviderDocumentController::class, 'reject']);
            Route::get('/', [ProviderDocumentController::class, 'index']);
        });

        Route::prefix('bookings')->group(function () {
            Route::post('/', [BookingController::class, 'store']);
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::get('/{id}/status', [BookingController::class, 'status']);
            Route::put('/{id}/cancel', [BookingController::class, 'cancel']);
            Route::put('/{id}/confirm', [BookingController::class, 'confirm']);
            Route::put('/{id}/complete', [BookingController::class, 'complete']);
            Route::get('/', [BookingController::class, 'index']);
        });

        Route::prefix('services')->group(function () {
            Route::post('/', [ServiceController::class, 'store']);
            Route::get('/{id}', [ServiceController::class, 'show']);
            Route::put('/{id}', [ServiceController::class, 'update']);
            Route::delete('/{id}', [ServiceController::class, 'destroy']);
            Route::get('/', [ServiceController::class, 'index']);
        });

        Route::prefix('notifications')->group(function () {
            Route::post('/send', [NotificationController::class, 'send']);
            Route::get('/{id}', [NotificationController::class, 'show']);
            Route::put('/preferences', [NotificationController::class, 'updatePreferences']);
            Route::get('/', [NotificationController::class, 'index']);
        });
        
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'index']);
            Route::get('/all', [MenuController::class, 'all']);
            Route::post('/', [MenuController::class, 'store']);
            Route::get('/{menu}', [MenuController::class, 'show']);
            Route::put('/{menu}', [MenuController::class, 'update']);
            Route::delete('/{menu}', [MenuController::class, 'destroy']);
            Route::post('/clear-cache', [MenuController::class, 'clearCache']);
        });
    });
    
    // Super Admin routes (no tenant scope)
    Route::middleware(['auth:sanctum', 'role:Super Admin'])->prefix('superadmin')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [SuperAdminUserController::class, 'index']);
            Route::post('/', [SuperAdminUserController::class, 'store']);
            Route::get('/{id}', [SuperAdminUserController::class, 'show']);
            Route::put('/{id}', [SuperAdminUserController::class, 'update']);
            Route::delete('/{id}', [SuperAdminUserController::class, 'destroy']);
            Route::post('/{id}/activate', [SuperAdminUserController::class, 'activate']);
            Route::post('/{id}/deactivate', [SuperAdminUserController::class, 'deactivate']);
        });
        
        Route::get('/roles', [SuperAdminUserController::class, 'getRoles']);
        Route::get('/tenants', [SuperAdminUserController::class, 'getTenants']);
    });
});