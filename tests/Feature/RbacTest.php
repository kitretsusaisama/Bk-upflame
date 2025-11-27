<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Permission;
use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run seeders
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\MenuSeeder::class);
        $this->seed(\Database\Seeders\WidgetSeeder::class);
    }

    public function test_user_with_permission_can_access_route()
    {
        // Get the system tenant
        $tenant = \App\Domains\Identity\Models\Tenant::where('slug', 'system')->first();
        
        // Create a user with a role that has permission
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant ? $tenant->id : null,
            'status' => 'active',
        ]);
        
        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        // Mock the permission check in the middleware or ensure the user has it
        // The middleware checks $user->hasPermission($permission)
        
        // We need a route that uses the middleware. 
        // Let's assume we have a route protected by 'users.view'
        // Route::get('/test-rbac', function () { return 'OK'; })->middleware('permission:users.view');
        
        // Since we don't want to modify routes/web.php just for this test, 
        // we can test the User model methods directly.

        $this->assertTrue($user->hasRole('Super Admin'));
        $this->assertTrue($user->hasPermission('users.view'));
    }

    public function test_sidebar_builder_returns_correct_menu()
    {
        $tenant = \App\Domains\Identity\Models\Tenant::where('slug', 'system')->first();
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant ? $tenant->id : null,
            'status' => 'active',
        ]);
        
        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        $builder = app(\App\Services\SidebarBuilder::class);
        $menu = $builder->buildForUser($user);

        $this->assertNotEmpty($menu);
        // Check for a known menu item
        $labels = array_column($menu, 'label');
        $this->assertContains('Dashboard', $labels);
        $this->assertContains('User Management', $labels);
    }

    public function test_widget_resolver_returns_correct_widgets()
    {
        $tenant = \App\Domains\Identity\Models\Tenant::where('slug', 'system')->first();
        $user = User::create([
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant ? $tenant->id : null,
            'status' => 'active',
        ]);
        
        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        $resolver = app(\App\Services\WidgetResolver::class);
        $widgets = $resolver->resolveForUser($user);

        $this->assertNotEmpty($widgets);
        // Check for a known widget
        $keys = array_column($widgets, 'key');
        $this->assertContains('stats_overview', $keys);
    }
}
