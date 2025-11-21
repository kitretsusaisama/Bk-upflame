@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')

@section('breadcrumb')
    <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Super Admin Dashboard</h1>
    <div class="page-actions">
        <button class="btn btn-primary">
            <span>➕</span> Create Tenant
        </button>
    </div>
</div>

<div class="stats-grid">
    @include('components.stat-card', [
        'icon' => '🏢',
        'value' => $stats['total_tenants'] ?? '0',
        'label' => 'Total Tenants',
        'change' => '+12%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-primary'
    ])
    
    @include('components.stat-card', [
        'icon' => '👥',
        'value' => $stats['total_users'] ?? '0',
        'label' => 'Total Users',
        'change' => '+8%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-success'
    ])
    
    @include('components.stat-card', [
        'icon' => '📊',
        'value' => $stats['active_sessions'] ?? '0',
        'label' => 'Active Sessions',
        'iconClass' => 'stat-icon-info'
    ])
    
    @include('components.stat-card', [
        'icon' => '💰',
        'value' => '$' . ($stats['revenue'] ?? '0'),
        'label' => 'Total Revenue',
        'change' => '+23%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-warning'
    ])
</div>

<div class="grid grid-2">
    @component('components.card', ['title' => 'Recent Tenants', 'actions' => '<a href="#" class="btn btn-sm btn-outline">View All</a>'])
        @component('components.table', [
            'headers' => ['Tenant', 'Domain', 'Status', 'Created', 'Actions']
        ])
            @forelse($tenants ?? [] as $tenant)
                <tr>
                    <td>
                        <div class="table-user">
                            <div class="user-avatar">{{ strtoupper(substr($tenant->name, 0, 1)) }}</div>
                            <div class="user-details">
                                <div class="user-name">{{ $tenant->name }}</div>
                                <div class="user-email">{{ $tenant->domain }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $tenant->domain }}</td>
                    <td>
                        @include('components.badge', [
                            'type' => $tenant->status === 'active' ? 'success' : 'secondary',
                            'slot' => ucfirst($tenant->status)
                        ])
                    </td>
                    <td>{{ $tenant->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="#" class="btn-icon" title="Edit">✏️</a>
                            <a href="#" class="btn-icon" title="View">👁️</a>
                            <a href="#" class="btn-icon text-danger" title="Delete">🗑️</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No tenants found</td>
                </tr>
            @endforelse
        @endcomponent
    @endcomponent
    
    @component('components.card', ['title' => 'System Status'])
        <div class="status-list">
            <div class="status-item">
                <div class="status-label">Database</div>
                <div class="status-value">
                    @include('components.badge', ['type' => 'success', 'slot' => 'Online'])
                </div>
            </div>
            <div class="status-item">
                <div class="status-label">Cache</div>
                <div class="status-value">
                    @include('components.badge', ['type' => 'success', 'slot' => 'Active'])
                </div>
            </div>
            <div class="status-item">
                <div class="status-label">Queue</div>
                <div class="status-value">
                    @include('components.badge', ['type' => 'success', 'slot' => 'Running'])
                </div>
            </div>
            <div class="status-item">
                <div class="status-label">Storage</div>
                <div class="status-value">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 65%"></div>
                    </div>
                    <span class="progress-text">65% used</span>
                </div>
            </div>
        </div>
    @endcomponent
</div>

<div class="grid grid-1">
    @component('components.card', ['title' => 'System Activity'])
        <div class="activity-timeline">
            <div class="activity-item">
                <div class="activity-icon activity-icon-success">✓</div>
                <div class="activity-content">
                    <div class="activity-title">New tenant created: Acme Corp</div>
                    <div class="activity-time">2 minutes ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon activity-icon-info">📧</div>
                <div class="activity-content">
                    <div class="activity-title">System backup completed</div>
                    <div class="activity-time">1 hour ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon activity-icon-warning">⚠</div>
                <div class="activity-content">
                    <div class="activity-title">High CPU usage detected</div>
                    <div class="activity-time">3 hours ago</div>
                </div>
            </div>
        </div>
    @endcomponent
</div>
@endsection
