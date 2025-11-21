<?php

namespace App\Http\Controllers;

use App\Domains\Identity\Repositories\TenantRepository;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    protected $tenantRepository;
    protected $userRepository;

    public function __construct(
        TenantRepository $tenantRepository,
        UserRepository $userRepository
    ) {
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $menuItems = [
            ['label' => 'Dashboard', 'route' => 'superadmin.dashboard', 'icon' => '📊'],
            ['label' => 'Tenants', 'route' => 'superadmin.tenants', 'icon' => '🏢', 'badge' => '12'],
            ['label' => 'Users', 'route' => 'superadmin.users', 'icon' => '👥'],
            ['label' => 'System', 'route' => 'superadmin.system', 'icon' => '⚙️'],
            ['separator' => true, 'label' => 'Analytics'],
            ['label' => 'Reports', 'route' => 'superadmin.reports', 'icon' => '📈'],
            ['label' => 'Logs', 'route' => 'superadmin.logs', 'icon' => '📋'],
        ];

        $stats = [
            'total_tenants' => $this->tenantRepository->findAll(1)->total(),
            'total_users' => $this->userRepository->findAll(1)->total(),
            'active_sessions' => rand(150, 300),
            'revenue' => number_format(rand(10000, 50000), 2),
        ];

        $tenants = $this->tenantRepository->findAll(10);

        return view('superadmin.dashboard', compact('menuItems', 'stats', 'tenants'));
    }

    public function tenants()
    {
        $menuItems = $this->getMenuItems();
        $tenants = $this->tenantRepository->findAll(20);

        return view('superadmin.tenants', compact('menuItems', 'tenants'));
    }

    public function system()
    {
        $menuItems = $this->getMenuItems();

        return view('superadmin.system', compact('menuItems'));
    }

    protected function getMenuItems()
    {
        return [
            ['label' => 'Dashboard', 'route' => 'superadmin.dashboard', 'icon' => '📊'],
            ['label' => 'Tenants', 'route' => 'superadmin.tenants', 'icon' => '🏢'],
            ['label' => 'Users', 'route' => 'superadmin.users', 'icon' => '👥'],
            ['label' => 'System', 'route' => 'superadmin.system', 'icon' => '⚙️'],
        ];
    }
}
