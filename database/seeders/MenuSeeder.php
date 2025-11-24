<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Menu\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus
        Menu::truncate();
        
        // Create MNC-grade, role-aware menu structure
        $this->createEnterpriseMenuStructure();
    }
    
    /**
     * Create enterprise-grade menu structure with role-based filtering
     */
    private function createEnterpriseMenuStructure(): void
    {
        // ================================
        // PROVIDER DASHBOARD MENU
        // ================================
        
        // Provider Dashboard
        $providerDashboard = Menu::create([
            'key' => 'provider-dashboard',
            'label' => 'Dashboard',
            'icon' => 'ti ti-dashboard',
            'route' => 'provider.dashboard',
            'permission' => 'view-provider-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Bookings
        $providerBookings = Menu::create([
            'key' => 'provider-bookings',
            'label' => 'Bookings',
            'icon' => 'ti ti-calendar',
            'route' => null,
            'permission' => 'view-provider-bookings',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-schedule',
            'label' => 'My Schedule',
            'route' => 'provider.schedule',
            'parent_id' => $providerBookings->id,
            'permission' => 'manage-provider-schedule',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-booking-requests',
            'label' => 'Booking Requests',
            'route' => 'provider.bookings',
            'parent_id' => $providerBookings->id,
            'permission' => 'view-provider-bookings',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-booking-history',
            'label' => 'Booking History',
            'route' => 'provider.bookings',
            'parent_id' => $providerBookings->id,
            'permission' => 'view-provider-bookings',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Workflows
        $providerWorkflows = Menu::create([
            'key' => 'provider-workflows',
            'label' => 'Workflows',
            'icon' => 'ti ti-file-text',
            'route' => null,
            'permission' => 'view-workflows',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-pending-tasks',
            'label' => 'Pending Tasks',
            'route' => 'provider.bookings',
            'parent_id' => $providerWorkflows->id,
            'permission' => 'view-provider-bookings',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-completed-tasks',
            'label' => 'Completed Tasks',
            'route' => 'provider.bookings',
            'parent_id' => $providerWorkflows->id,
            'permission' => 'view-provider-bookings',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Availability
        $providerAvailability = Menu::create([
            'key' => 'provider-availability',
            'label' => 'Availability',
            'icon' => 'ti ti-clock',
            'route' => null,
            'permission' => 'manage-provider-schedule',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-set-availability',
            'label' => 'Set Availability',
            'route' => 'provider.schedule',
            'parent_id' => $providerAvailability->id,
            'permission' => 'manage-provider-schedule',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-my-services',
            'label' => 'My Services',
            'route' => 'provider.services',
            'parent_id' => $providerAvailability->id,
            'permission' => 'manage-provider-services',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Profile
        $providerProfile = Menu::create([
            'key' => 'provider-profile',
            'label' => 'Profile',
            'icon' => 'ti ti-user',
            'route' => null,
            'permission' => 'view-provider-dashboard', // Role-specific permission
            'order' => 5,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-edit-profile',
            'label' => 'Edit Profile',
            'route' => 'provider.profile',
            'parent_id' => $providerProfile->id,
            'permission' => 'view-provider-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Security
        $providerSecurity = Menu::create([
            'key' => 'provider-security',
            'label' => 'Security',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
            'parent_id' => $providerProfile->id,
        ]);
        
        Menu::create([
            'key' => 'provider-change-password',
            'label' => 'Change Password',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $providerSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-mfa',
            'label' => 'Multi-Factor Authentication',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $providerSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Provider Support
        $providerSupport = Menu::create([
            'key' => 'provider-support',
            'label' => 'Support',
            'icon' => 'ti ti-help',
            'route' => null,
            'permission' => 'view-provider-dashboard', // Role-specific permission
            'order' => 6,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-help-center',
            'label' => 'Help Center',
            'route' => null, // No specific route for this
            'parent_id' => $providerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-support-tickets',
            'label' => 'Support Tickets',
            'route' => null, // No specific route for this
            'parent_id' => $providerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'provider-documentation',
            'label' => 'Documentation',
            'route' => null, // No specific route for this
            'parent_id' => $providerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // ================================
        // TENANT ADMIN DASHBOARD MENU
        // ================================
        
        // Tenant Admin Dashboard
        $tenantAdminDashboard = Menu::create([
            'key' => 'tenantadmin-dashboard',
            'label' => 'Dashboard',
            'icon' => 'ti ti-dashboard',
            'route' => 'tenantadmin.dashboard',
            'permission' => 'view-tenantadmin-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Management
        $tenantAdminManagement = Menu::create([
            'key' => 'tenantadmin-management',
            'label' => 'Management',
            'icon' => 'ti ti-settings',
            'route' => null,
            'permission' => 'manage-tenant',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-users',
            'label' => 'Users',
            'route' => 'tenantadmin.users',
            'parent_id' => $tenantAdminManagement->id,
            'permission' => 'manage-tenant-users',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-providers',
            'label' => 'Providers',
            'route' => 'tenantadmin.providers',
            'parent_id' => $tenantAdminManagement->id,
            'permission' => 'manage-providers',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-bookings',
            'label' => 'Bookings',
            'route' => 'tenantadmin.bookings',
            'parent_id' => $tenantAdminManagement->id,
            'permission' => 'manage-bookings',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-workflows',
            'label' => 'Workflows',
            'route' => 'tenantadmin.bookings',
            'parent_id' => $tenantAdminManagement->id,
            'permission' => 'manage-bookings',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-roles-permissions',
            'label' => 'Roles & Permissions',
            'route' => 'tenantadmin.roles',
            'parent_id' => $tenantAdminManagement->id,
            'permission' => 'manage-roles',
            'order' => 5,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Users Module
        $tenantAdminUsers = Menu::create([
            'key' => 'tenantadmin-users-module',
            'label' => 'Users',
            'icon' => 'ti ti-users',
            'route' => null,
            'permission' => 'view-users',
            'order' => 6,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-users-all',
            'label' => 'All Users',
            'route' => 'tenantadmin.users',
            'parent_id' => $tenantAdminUsers->id,
            'permission' => 'view-users',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-users-roles-permissions',
            'label' => 'Roles & Permissions',
            'route' => 'tenantadmin.roles',
            'parent_id' => $tenantAdminUsers->id,
            'permission' => 'manage-roles',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Roles Module
        $tenantAdminRoles = Menu::create([
            'key' => 'tenantadmin-roles-module',
            'label' => 'Roles',
            'icon' => 'ti ti-shield-lock',
            'route' => null,
            'permission' => 'manage-roles',
            'order' => 7,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-roles-all',
            'label' => 'All Roles',
            'route' => 'tenantadmin.roles',
            'parent_id' => $tenantAdminRoles->id,
            'permission' => 'manage-roles',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-roles-permissions-list',
            'label' => 'Permissions',
            'route' => 'tenantadmin.permissions',
            'parent_id' => $tenantAdminRoles->id,
            'permission' => 'manage-permissions',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Settings Module
        $tenantAdminSettings = Menu::create([
            'key' => 'tenantadmin-settings-module',
            'label' => 'Settings',
            'icon' => 'ti ti-settings',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 8,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-settings-profile',
            'label' => 'Profile',
            'route' => 'tenantadmin.profile',
            'parent_id' => $tenantAdminSettings->id,
            'permission' => 'update-profile',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-settings-security',
            'label' => 'Security',
            'route' => 'tenantadmin.security',
            'parent_id' => $tenantAdminSettings->id,
            'permission' => 'manage-security',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Configuration
        $tenantAdminConfig = Menu::create([
            'key' => 'tenantadmin-configuration',
            'label' => 'Configuration',
            'icon' => 'ti ti-tool',
            'route' => null,
            'permission' => 'manage-tenant',
            'order' => 9,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-settings',
            'label' => 'Tenant Settings',
            'route' => 'tenantadmin.settings',
            'parent_id' => $tenantAdminConfig->id,
            'permission' => 'manage-tenant',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-domain-settings',
            'label' => 'Domain Settings',
            'route' => 'tenantadmin.settings',
            'parent_id' => $tenantAdminConfig->id,
            'permission' => 'manage-tenant',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-email-settings',
            'label' => 'Email / Notifications Settings',
            'route' => 'tenantadmin.settings',
            'parent_id' => $tenantAdminConfig->id,
            'permission' => 'manage-tenant',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Profile
        $tenantAdminProfile = Menu::create([
            'key' => 'tenantadmin-profile',
            'label' => 'Profile',
            'icon' => 'ti ti-user',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 10,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-edit-profile',
            'label' => 'Edit Profile',
            'route' => 'tenantadmin.settings',
            'parent_id' => $tenantAdminProfile->id,
            'permission' => 'view-tenantadmin-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Security
        $tenantAdminSecurity = Menu::create([
            'key' => 'tenantadmin-security',
            'label' => 'Security',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
            'parent_id' => $tenantAdminProfile->id,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-change-password',
            'label' => 'Change Password',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $tenantAdminSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-mfa',
            'label' => 'Multi-Factor Authentication',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $tenantAdminSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Tenant Admin Support
        $tenantAdminSupport = Menu::create([
            'key' => 'tenantadmin-support',
            'label' => 'Support',
            'icon' => 'ti ti-help',
            'route' => null,
            'permission' => null,
            'order' => 11,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-help-center',
            'label' => 'Help Center',
            'route' => null, // No specific route for this
            'parent_id' => $tenantAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-support-tickets',
            'label' => 'Support Tickets',
            'route' => null, // No specific route for this
            'parent_id' => $tenantAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'tenantadmin-documentation',
            'label' => 'Documentation',
            'route' => null, // No specific route for this
            'parent_id' => $tenantAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // ================================
        // SUPER ADMIN DASHBOARD MENU
        // ================================
        
        // Super Admin Dashboard
        $superAdminDashboard = Menu::create([
            'key' => 'superadmin-dashboard',
            'label' => 'Dashboard',
            'icon' => 'ti ti-dashboard',
            'route' => 'superadmin.dashboard',
            'permission' => 'view-superadmin-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Tenants
        $superAdminTenants = Menu::create([
            'key' => 'superadmin-tenants',
            'label' => 'Tenants',
            'icon' => 'ti ti-building',
            'route' => null,
            'permission' => 'manage-tenants',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-all-tenants',
            'label' => 'All Tenants',
            'route' => 'superadmin.tenants',
            'parent_id' => $superAdminTenants->id,
            'permission' => 'manage-tenants',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-create-tenant',
            'label' => 'Create Tenant',
            'route' => 'superadmin.tenants',
            'parent_id' => $superAdminTenants->id,
            'permission' => 'manage-tenants',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-suspended-tenants',
            'label' => 'Suspended Tenants',
            'route' => 'superadmin.tenants',
            'parent_id' => $superAdminTenants->id,
            'permission' => 'manage-tenants',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-tenant-usage',
            'label' => 'Tenant Usage & Billing',
            'route' => 'superadmin.tenants',
            'parent_id' => $superAdminTenants->id,
            'permission' => 'manage-tenants',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Users
        $superAdminUsers = Menu::create([
            'key' => 'superadmin-users',
            'label' => 'Users (Global)',
            'icon' => 'ti ti-users',
            'route' => null,
            'permission' => 'manage-system-users',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-all-users',
            'label' => 'All Users',
            'route' => 'superadmin.users',
            'parent_id' => $superAdminUsers->id,
            'permission' => 'manage-system-users',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-global-roles',
            'label' => 'Roles & Permissions (Global)',
            'route' => 'superadmin.users',
            'parent_id' => $superAdminUsers->id,
            'permission' => 'manage-system-users',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-activity-logs',
            'label' => 'Activity Logs',
            'route' => 'superadmin.logs',
            'parent_id' => $superAdminUsers->id,
            'permission' => 'view-system-reports',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin System Management
        $superAdminSystem = Menu::create([
            'key' => 'superadmin-system-management',
            'label' => 'System Management',
            'icon' => 'ti ti-settings',
            'route' => null,
            'permission' => 'manage-system',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-modules',
            'label' => 'Modules & Feature Flags',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-integrations',
            'label' => 'Integrations (SSO, SMTP, SMS, Payment)',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-api-keys',
            'label' => 'API Keys',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-global-billing',
            'label' => 'Global Billing Plans',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-audit-logs',
            'label' => 'Audit Logs',
            'route' => 'superadmin.logs',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'view-system-reports',
            'order' => 5,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-health-monitoring',
            'label' => 'Health Monitoring',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 6,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-worker-queue',
            'label' => 'Worker Queue Dashboard',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminSystem->id,
            'permission' => 'manage-system',
            'order' => 7,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Configuration
        $superAdminConfig = Menu::create([
            'key' => 'superadmin-configuration',
            'label' => 'Configuration',
            'icon' => 'ti ti-tool',
            'route' => null,
            'permission' => 'manage-system',
            'order' => 5,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-environment',
            'label' => 'Environment Configuration',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminConfig->id,
            'permission' => 'manage-system',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-maintenance',
            'label' => 'Maintenance Mode',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminConfig->id,
            'permission' => 'manage-system',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-backup-restore',
            'label' => 'Backup & Restore',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminConfig->id,
            'permission' => 'manage-system',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-global-settings',
            'label' => 'Global Settings',
            'route' => 'superadmin.system',
            'parent_id' => $superAdminConfig->id,
            'permission' => 'manage-system',
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Profile
        $superAdminProfile = Menu::create([
            'key' => 'superadmin-profile',
            'label' => 'Profile',
            'icon' => 'ti ti-user',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 6,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-edit-profile',
            'label' => 'Edit Profile',
            'route' => 'superadmin.profile',
            'parent_id' => $superAdminProfile->id,
            'permission' => 'view-superadmin-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Security
        $superAdminSecurity = Menu::create([
            'key' => 'superadmin-security',
            'label' => 'Security',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
            'parent_id' => $superAdminProfile->id,
        ]);
        
        Menu::create([
            'key' => 'superadmin-change-password',
            'label' => 'Change Password',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $superAdminSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-mfa',
            'label' => 'Multi-Factor Authentication',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $superAdminSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Super Admin Support
        $superAdminSupport = Menu::create([
            'key' => 'superadmin-support',
            'label' => 'Support',
            'icon' => 'ti ti-help',
            'route' => null,
            'permission' => null,
            'order' => 7,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-help-center',
            'label' => 'Help Center',
            'route' => null, // No specific route for this
            'parent_id' => $superAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-support-tickets',
            'label' => 'Support Tickets',
            'route' => null, // No specific route for this
            'parent_id' => $superAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'superadmin-documentation',
            'label' => 'Documentation',
            'route' => null, // No specific route for this
            'parent_id' => $superAdminSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // ================================
        // CUSTOMER DASHBOARD MENU
        // ================================
        
        // Customer Dashboard
        $customerDashboard = Menu::create([
            'key' => 'customer-dashboard',
            'label' => 'Dashboard',
            'icon' => 'ti ti-dashboard',
            'route' => 'customer.dashboard',
            'permission' => 'view-customer-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Customer Bookings
        $customerBookings = Menu::create([
            'key' => 'customer-bookings',
            'label' => 'Bookings',
            'icon' => 'ti ti-calendar',
            'route' => null,
            'permission' => 'view-customer-bookings',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-my-bookings',
            'label' => 'My Bookings',
            'route' => 'customer.bookings',
            'parent_id' => $customerBookings->id,
            'permission' => 'view-customer-bookings',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-upcoming-bookings',
            'label' => 'Upcoming Bookings',
            'route' => 'customer.bookings',
            'parent_id' => $customerBookings->id,
            'permission' => 'view-customer-bookings',
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-booking-history',
            'label' => 'Booking History',
            'route' => 'customer.bookings',
            'parent_id' => $customerBookings->id,
            'permission' => 'view-customer-bookings',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Customer Providers
        $customerProviders = Menu::create([
            'key' => 'customer-providers',
            'label' => 'Providers',
            'icon' => 'ti ti-users',
            'route' => null,
            'permission' => 'browse-services',
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-provider-directory',
            'label' => 'Provider Directory',
            'route' => 'customer.services',
            'parent_id' => $customerProviders->id,
            'permission' => 'browse-services',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Customer Profile
        $customerProfile = Menu::create([
            'key' => 'customer-profile',
            'label' => 'Profile',
            'icon' => 'ti ti-user',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 4,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-edit-profile',
            'label' => 'Edit Profile',
            'route' => 'customer.profile',
            'parent_id' => $customerProfile->id,
            'permission' => 'view-customer-dashboard',
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Customer Security
        $customerSecurity = Menu::create([
            'key' => 'customer-security',
            'label' => 'Security',
            'route' => null,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
            'parent_id' => $customerProfile->id,
        ]);
        
        Menu::create([
            'key' => 'customer-change-password',
            'label' => 'Change Password',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $customerSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-mfa',
            'label' => 'Multi-Factor Authentication',
            'route' => null, // No specific route for this, handled in profile pages
            'parent_id' => $customerSecurity->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        // Customer Support
        $customerSupport = Menu::create([
            'key' => 'customer-support',
            'label' => 'Support',
            'icon' => 'ti ti-help',
            'route' => null,
            'permission' => null,
            'order' => 5,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-help-center',
            'label' => 'Help Center',
            'route' => null, // No specific route for this
            'parent_id' => $customerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 1,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-support-tickets',
            'label' => 'Support Tickets',
            'route' => null, // No specific route for this
            'parent_id' => $customerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 2,
            'type' => 'link',
            'is_enabled' => true,
        ]);
        
        Menu::create([
            'key' => 'customer-documentation',
            'label' => 'Documentation',
            'route' => null, // No specific route for this
            'parent_id' => $customerSupport->id,
            'permission' => null, // Available to all authenticated users
            'order' => 3,
            'type' => 'link',
            'is_enabled' => true,
        ]);
    }
}