@extends('layouts.dashboard')

@section('title', 'Tenant Admin Dashboard')

@section('breadcrumb')
    <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tenant Admin Dashboard</h1>
    <div class="page-actions">
        <button class="btn btn-primary">
            <span>➕</span> Add User
        </button>
    </div>
</div>

<div class="stats-grid">
    @include('components.stat-card', [
        'icon' => '👥',
        'value' => $stats['total_users'] ?? '0',
        'label' => 'Total Users',
        'change' => '+5%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-primary'
    ])
    
    @include('components.stat-card', [
        'icon' => '🔧',
        'value' => $stats['total_providers'] ?? '0',
        'label' => 'Active Providers',
        'change' => '+3%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-success'
    ])
    
    @include('components.stat-card', [
        'icon' => '📅',
        'value' => $stats['total_bookings'] ?? '0',
        'label' => 'Total Bookings',
        'change' => '+15%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-info'
    ])
    
    @include('components.stat-card', [
        'icon' => '⏳',
        'value' => $stats['pending_workflows'] ?? '0',
        'label' => 'Pending Workflows',
        'iconClass' => 'stat-icon-warning'
    ])
</div>

<div class="grid grid-2">
    @component('components.card', ['title' => 'Recent Users', 'actions' => '<a href="#" class="btn btn-sm btn-outline">View All</a>'])
        @component('components.table', [
            'headers' => ['User', 'Role', 'Status', 'Joined', 'Actions']
        ])
            @forelse($users ?? [] as $user)
                <tr>
                    <td>
                        <div class="table-user">
                            <div class="user-avatar">{{ strtoupper(substr($user->email, 0, 1)) }}</div>
                            <div class="user-details">
                                <div class="user-name">{{ $user->profile->first_name ?? 'N/A' }} {{ $user->profile->last_name ?? '' }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->roles->first()->name ?? 'No Role' }}</td>
                    <td>
                        @include('components.badge', [
                            'type' => $user->status === 'active' ? 'success' : 'warning',
                            'slot' => ucfirst($user->status)
                        ])
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="#" class="btn-icon" title="Edit">✏️</a>
                            <a href="#" class="btn-icon" title="View">👁️</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
            @endforelse
        @endcomponent
    @endcomponent
    
    @component('components.card', ['title' => 'Pending Approvals'])
        <div class="approval-list">
            @forelse($pendingWorkflows ?? [] as $workflow)
                <div class="approval-item">
                    <div class="approval-icon">📋</div>
                    <div class="approval-content">
                        <div class="approval-title">{{ $workflow->definition->name }}</div>
                        <div class="approval-meta">
                            <span>{{ $workflow->entity_type }}</span>
                            <span class="meta-sep">•</span>
                            <span>{{ $workflow->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="approval-actions">
                        <button class="btn btn-sm btn-success">Approve</button>
                        <button class="btn btn-sm btn-error">Reject</button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>No pending approvals</p>
                </div>
            @endforelse
        </div>
    @endcomponent
</div>

<div class="grid grid-1">
    @component('components.card', ['title' => 'Quick Actions'])
        <div class="quick-actions">
            <a href="#" class="quick-action-card">
                <div class="quick-action-icon">👤</div>
                <div class="quick-action-title">Manage Users</div>
            </a>
            <a href="#" class="quick-action-card">
                <div class="quick-action-icon">🔑</div>
                <div class="quick-action-title">Roles & Permissions</div>
            </a>
            <a href="#" class="quick-action-card">
                <div class="quick-action-icon">⚙️</div>
                <div class="quick-action-title">Settings</div>
            </a>
            <a href="#" class="quick-action-card">
                <div class="quick-action-icon">📊</div>
                <div class="quick-action-title">Reports</div>
            </a>
        </div>
    @endcomponent
</div>
@endsection
