<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domains\Access\Services\AccessEvaluationService;
use App\Domains\Access\Services\RoleService;
use App\Domains\Access\Services\PermissionService;
use App\Domains\Identity\Models\User;
use Mockery as m;
use Illuminate\Support\Facades\Cache;

class AccessEvaluationServiceTest extends TestCase
{
    /** @test */
    public function it_returns_true_for_super_admin_user()
    {
        // Create mocks
        $roleService = m::mock(RoleService::class);
        $permissionService = m::mock(PermissionService::class);
        
        // Create the service
        $service = new AccessEvaluationService($roleService, $permissionService);
        
        // Create a user with Super Admin role
        $user = m::mock(User::class);
        $user->shouldReceive('hasRole')->with('Super Admin')->andReturn(true);
        
        // Test the service
        $result = $service->can($user, 'any.permission');
        
        // Assert the result
        $this->assertTrue($result);
    }
    
    /** @test */
    public function it_returns_false_when_user_does_not_have_permission()
    {
        // Create mocks
        $roleService = m::mock(RoleService::class);
        $permissionService = m::mock(PermissionService::class);
        
        // Create the service
        $service = new AccessEvaluationService($roleService, $permissionService);
        
        // Create a user without Super Admin role
        $user = m::mock(User::class);
        $user->shouldReceive('hasRole')->with('Super Admin')->andReturn(false);
        
        // Mock cache to return empty permissions
        Cache::shouldReceive('get')->andReturn(null);
        Cache::shouldReceive('put')->andReturn(true);
        
        // Mock user roles and permissions
        $user->shouldReceive('load')->andReturnSelf();
        $user->shouldReceive('roles')->andReturn(collect([]));
        
        // Test the service
        $result = $service->can($user, 'some.permission');
        
        // Assert the result
        $this->assertFalse($result);
    }
}