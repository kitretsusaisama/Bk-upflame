<?php

namespace App\Http\Controllers;

use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Provider\Repositories\ProviderRepository;
use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Workflow\Repositories\WorkflowRepository;
use Illuminate\Http\Request;

class TenantAdminController extends Controller
{
    protected $userRepository;
    protected $providerRepository;
    protected $bookingRepository;
    protected $workflowRepository;

    public function __construct(
        UserRepository $userRepository,
        ProviderRepository $providerRepository,
        BookingRepository $bookingRepository,
        WorkflowRepository $workflowRepository
    ) {
        $this->userRepository = $userRepository;
        $this->providerRepository = $providerRepository;
        $this->bookingRepository = $bookingRepository;
        $this->workflowRepository = $workflowRepository;
    }

    public function dashboard()
    {
        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id;

        $menuItems = [
            ['label' => 'Dashboard', 'route' => 'tenantadmin.dashboard', 'icon' => '📊'],
            ['label' => 'Users', 'route' => 'tenantadmin.users', 'icon' => '👥'],
            ['label' => 'Providers', 'route' => 'tenantadmin.providers', 'icon' => '🔧'],
            ['label' => 'Bookings', 'route' => 'tenantadmin.bookings', 'icon' => '📅'],
            ['separator' => true, 'label' => 'Management'],
            ['label' => 'Roles & Permissions', 'route' => 'tenantadmin.roles', 'icon' => '🔑'],
            ['label' => 'Settings', 'route' => 'tenantadmin.settings', 'icon' => '⚙️'],
        ];

        $stats = [
            'total_users' => $this->userRepository->findByTenant($tenantId, 1)->total(),
            'total_providers' => $this->providerRepository->findByTenant($tenantId, 1)->total(),
            'total_bookings' => $this->bookingRepository->findByTenant($tenantId, 1)->total(),
            'pending_workflows' => $this->workflowRepository->findByStatus('pending', $tenantId, 1)->total(),
        ];

        $users = $this->userRepository->findByTenant($tenantId, 10);
        $pendingWorkflows = $this->workflowRepository->findByStatus('pending', $tenantId, 5);

        return view('tenantadmin.dashboard', compact('menuItems', 'stats', 'users', 'pendingWorkflows'));
    }

    public function users()
    {
        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id;

        $menuItems = $this->getMenuItems();
        $users = $this->userRepository->findByTenant($tenantId, 20);

        return view('tenantadmin.users', compact('menuItems', 'users'));
    }

    protected function getMenuItems()
    {
        return [
            ['label' => 'Dashboard', 'route' => 'tenantadmin.dashboard', 'icon' => '📊'],
            ['label' => 'Users', 'route' => 'tenantadmin.users', 'icon' => '👥'],
            ['label' => 'Providers', 'route' => 'tenantadmin.providers', 'icon' => '🔧'],
            ['label' => 'Bookings', 'route' => 'tenantadmin.bookings', 'icon' => '📅'],
            ['separator' => true, 'label' => 'Management'],
            ['label' => 'Roles & Permissions', 'route' => 'tenantadmin.roles', 'icon' => '🔑'],
            ['label' => 'Settings', 'route' => 'tenantadmin.settings', 'icon' => '⚙️'],
        ];
    }
}