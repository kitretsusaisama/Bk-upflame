@extends('layouts.dashboard')

@section('title', 'Operations Dashboard')

@section('breadcrumb')
    <span>Operations</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Operations Dashboard</h1>
    <div class="page-actions">
        <button class="btn btn-outline">
            <span>📊</span> Generate Report
        </button>
        <button class="btn btn-primary">
            <span>📋</span> New Workflow
        </button>
    </div>
</div>

<div class="stats-grid">
    @include('components.stat-card', [
        'icon' => '📋',
        'value' => $stats['active_workflows'] ?? '0',
        'label' => 'Active Workflows',
        'iconClass' => 'stat-icon-primary'
    ])
    
    @include('components.stat-card', [
        'icon' => '⏳',
        'value' => $stats['pending_approvals'] ?? '0',
        'label' => 'Pending Approvals',
        'iconClass' => 'stat-icon-warning'
    ])
    
    @include('components.stat-card', [
        'icon' => '✅',
        'value' => $stats['completed_today'] ?? '0',
        'label' => 'Completed Today',
        'change' => '+8%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-success'
    ])
    
    @include('components.stat-card', [
        'icon' => '📊',
        'value' => $stats['avg_completion_time'] ?? '0',
        'label' => 'Avg. Completion (days)',
        'iconClass' => 'stat-icon-info'
    ])
</div>

<div class="grid grid-2">
    @component('components.card', ['title' => 'Active Workflows', 'actions' => '<a href="#" class="btn btn-sm btn-outline">View All</a>'])
        @component('components.table', [
            'headers' => ['Workflow', 'Entity', 'Current Step', 'Status', 'Actions']
        ])
            @forelse($activeWorkflows ?? [] as $workflow)
                <tr>
                    <td>
                        <div class="workflow-name">{{ $workflow->definition->name }}</div>
                        <div class="text-muted">#{{ $workflow->id }}</div>
                    </td>
                    <td>
                        <div>{{ ucfirst($workflow->entity_type) }}</div>
                        <div class="text-muted">{{ Str::limit($workflow->entity_id, 8) }}</div>
                    </td>
                    <td>{{ $workflow->current_step_key ?? 'N/A' }}</td>
                    <td>
                        @include('components.badge', [
                            'type' => $workflow->status === 'approved' ? 'success' : ($workflow->status === 'rejected' ? 'error' : 'info'),
                            'slot' => ucfirst($workflow->status)
                        ])
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="#" class="btn-icon" title="View">👁️</a>
                            <a href="#" class="btn-icon" title="Approve">✅</a>
                            <a href="#" class="btn-icon" title="Reject">❌</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No active workflows</td>
                </tr>
            @endforelse
        @endcomponent
    @endcomponent
    
    @component('components.card', ['title' => 'Workflow Performance'])
        <div class="performance-metrics">
            <div class="metric-item">
                <div class="metric-label">Average Processing Time</div>
                <div class="metric-value">2.5 days</div>
                <div class="metric-trend positive">↓ 15% faster</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Approval Rate</div>
                <div class="metric-value">94%</div>
                <div class="metric-trend positive">↑ 3% higher</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Pending Items</div>
                <div class="metric-value">{{ $stats['pending_approvals'] ?? '0' }}</div>
            </div>
        </div>
        
        <div class="workflow-chart">
            <div class="chart-bar">
                <div class="chart-label">Approved</div>
                <div class="chart-bar-fill" style="width: 75%; background: #10b981;">75%</div>
            </div>
            <div class="chart-bar">
                <div class="chart-label">Pending</div>
                <div class="chart-bar-fill" style="width: 15%; background: #f59e0b;">15%</div>
            </div>
            <div class="chart-bar">
                <div class="chart-label">Rejected</div>
                <div class="chart-bar-fill" style="width: 10%; background: #ef4444;">10%</div>
            </div>
        </div>
    @endcomponent
</div>

<div class="grid grid-1">
    @component('components.card', ['title' => 'Recent Activity'])
        <div class="activity-timeline">
            @for($i = 1; $i <= 5; $i++)
                <div class="activity-item">
                    <div class="activity-icon activity-icon-{{ ['success', 'info', 'warning'][rand(0,2)] }}">
                        {{ ['✓', '📋', '⏳'][rand(0,2)] }}
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Workflow {{ ['approved', 'submitted', 'updated'][rand(0,2)] }}: Provider Onboarding #{{ rand(1000, 9999) }}</div>
                        <div class="activity-meta">
                            <span>By John Doe</span>
                            <span class="meta-sep">•</span>
                            <span>{{ now()->subMinutes(rand(5, 120))->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    @endcomponent
</div>
@endsection
