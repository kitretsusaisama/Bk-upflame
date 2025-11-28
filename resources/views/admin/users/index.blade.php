@extends('dashboard.layout')

@section('page-title', 'Users Management')

@section('content')
<div class="users-page">
    <div class="ms-container" x-data="{ filtersOpen: false }">
        
        {{-- Page Header --}}
        <div class="ms-page-header">
            <h1 class="ms-page-title">👥 Users Management</h1>
            <a href="{{ route('admin.users.create') }}" class="ms-button ms-button-primary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Create User
            </a>
        </div>

        {{-- Filters Panel --}}
        <div class="ms-card">
            <button @click="filtersOpen = !filtersOpen" class="ms-filters-toggle">
                <span>🔍 Advanced Filters</span>
                <svg class="chevron-rotate" :class="{ 'rotate': filtersOpen }" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div x-show="filtersOpen" x-collapse class="ms-filters-content">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    {{-- Tenant Filter --}}
                    <div class="ms-form-field">
                        <label class="ms-label">Tenant</label>
                        <select name="tenant_id" class="ms-select">
                            <option value="">All Tenants</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Role Filter --}}
                    <div class="ms-form-field">
                        <label class="ms-label">Role</label>
                        <select name="role_id" class="ms-select">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div class="ms-form-field">
                        <label class="ms-label">Status</label>
                        <select name="status" class="ms-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="ms-form-field">
                        <label class="ms-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by email..." class="ms-input">
                    </div>

                    {{-- Filter Actions --}}
                    <div class="flex items-end gap-3 col-span-full">
                        <button type="submit" class="ms-button ms-button-primary">Apply Filters</button>
                        <a href="{{ route('admin.users.index') }}" class="ms-button ms-button-secondary">Clear All</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <h2 class="ms-card-title">All Users ({{ $users->total() }})</h2>
                <span style="font-size: 0.875rem; color: var(--ms-gray-300);">{{ $users->count() }} of {{ $users->total() }} users displayed</span>
            </div>

            <div class="ms-table-container">
                <table class="ms-table">
                    <thead>
                        <tr>
                            <th class="sortable">User</th>
                            <th class="sortable">Tenant</th>
                            <th>Roles</th>
                            <th class="sortable">Status</th>
                            <th class="sortable">Last Login</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                {{-- User --}}
                                <td>
                                    <div class="ms-user-cell">
                                        <div class="ms-user-avatar">
                                            {{ strtoupper(substr($user->email, 0, 1)) }}
                                        </div>
                                        <div class="ms-user-info">
                                            <div class="ms-user-email">
                                                {{ $user->email }}
                                                @if($user->email_verified)
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="color: var(--ms-success);">
                                                        <path d="M13.5 4L6 11.5 2.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            @if($user->profile)
                                                <div class="ms-user-meta">{{ $user->profile->name ?? 'No name' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Tenant --}}
                                <td>
                                    @if($user->tenant)
                                        <span class="ms-badge ms-badge-info">{{ $user->tenant->name }}</span>
                                    @else
                                        <span class="ms-badge ms-badge-gray">Platform</span>
                                    @endif
                                </td>

                                {{-- Roles --}}
                                <td>
                                    <div style="display: flex; gap: 0.375rem; flex-wrap: wrap;">
                                        @forelse($user->roles as $role)
                                            <span class="ms-badge ms-badge-info">{{ $role->name }}</span>
                                        @empty
                                            <span style="color: var(--ms-gray-300); font-size: 0.8125rem;">No roles assigned</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td>
                                    @switch($user->status)
                                        @case('active')
                                            <span class="ms-badge ms-badge-success">✓ Active</span>
                                            @break
                                        @case('inactive')
                                            <span class="ms-badge ms-badge-gray">○ Inactive</span>
                                            @break
                                        @default
                                            <span class="ms-badge ms-badge-danger">⊘ Suspended</span>
                                    @endswitch
                                </td>

                                {{-- Last Login --}}
                                <td style="color: var(--ms-gray-300); font-size: 0.8125rem;">
                                    {{ $user->last_login?->diffForHumans() ?? 'Never' }}
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <a href="{{ route('admin.users.show', $user) }}" class="ms-icon-button" title="View">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.5"/>
                                                <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.users.edit', $user) }}" class="ms-icon-button" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M11.5 2.5l2 2L6 12l-3 1 1-3 7.5-7.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>

                                        @if($user->status === 'active')
                                            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                                                @csrf
                                                <button class="ms-icon-button" title="Deactivate">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                                                        <path d="M3 3l10 10" stroke="currentColor" stroke-width="1.5"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                                                @csrf
                                                <button class="ms-icon-button" title="Activate">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M13.5 4L6 11.5 2.5 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ms-icon-button danger" title="Delete">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M3 4h10M6 4V3a1 1 0 011-1h2a1 1 0 011 1v1m2 0v9a1 1 0 01-1 1H5a1 1 0 01-1-1V4h8z" stroke="currentColor" stroke-width="1.5"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="ms-empty-state">
                                        <svg class="ms-empty-icon" viewBox="0 0 64 64" fill="none">
                                            <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="2"/>
                                            <path d="M20 32h6M38 32h6M24 40c0-4 4-8 8-8s8 4 8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        <p class="ms-empty-text">No users found matching your criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="ms-pagination">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>

    </div>
</div>
@endsection
