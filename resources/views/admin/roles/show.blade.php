@extends('layouts.dashboard')

@section('title', 'Role Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Role: {{ $role->name }}</h2>
                <div>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Role
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {{-- Role Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Role Information</strong>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Name:</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $role->description ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tenant:</th>
                            <td>
                                @if($role->tenant)
                                    <span class="badge badge-info">{{ $role->tenant->name }}</span>
                                @else
                                    <span class="badge badge-secondary">Platform Level</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Priority:</th>
                            <td>
                                <span class="badge badge-{{ $role->priority <= 10 ? 'danger' : ($role->priority <= 50 ? 'warning' : 'secondary') }}">
                                    {{ $role->priority }}
                                </span>
                                <small class="text-muted">(Lower = Higher priority)</small>
                            </td>
                        </tr>
                        <tr>
                            <th>System Role:</th>
                            <td>
                                @if($role->is_system)
                                    <i class="fas fa-shield-alt text-primary"></i> Yes
                                @else
                                    No
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $role->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Assigned Permissions --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Assigned Permissions ({{ $role->permissions->count() }})</strong>
                </div>
                <div class="card-body">
                    @if($permissions->count() > 0)
                        @foreach($permissions as $resource => $perms)
                            <h6 class="mt-3 mb-2">{{ ucfirst(str_replace('_', ' ', $resource)) }}</h6>
                            <div class="row">
                                @foreach($perms as $permission)
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>{{ $permission->name }}</strong>
                                            @if($permission->description)
                                                <br><small class="text-muted ml-4">{{ $permission->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">No permissions assigned to this role.</p>
                    @endif
                </div>
            </div>

            {{-- Users with this Role --}}
            <div class="card">
                <div class="card-header">
                    <strong>Users with this Role ({{ $role->users->count() }})</strong>
                </div>
                <div class="card-body">
                    @if($role->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Tenant</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users as $user)
                                        <tr>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->tenant)
                                                    <span class="badge badge-sm badge-info">{{ $user->tenant->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->status === 'active')
                                                    <span class="badge badge-sm badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-sm badge-secondary">{{ ucfirst($user->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No users are assigned this role.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Quick Stats --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Quick Stats</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $role->permissions->count() }}</h4>
                        <small class="text-muted">Permissions</small>
                    </div>
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $role->users->count() }}</h4>
                        <small class="text-muted">Users</small>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <strong>Actions</strong>
                </div>
                <div class="card-body">
                    @if(!($role->is_system && $role->name === 'Super Admin'))
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Edit Role
                        </a>
                        
                        @if($role->users->count() == 0 && !$role->is_system)
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete Role
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-block" disabled>
                                <i class="fas fa-lock"></i> Cannot Delete
                            </button>
                            <small class="text-muted">
                                @if($role->is_system)
                                    System roles cannot be deleted.
                                @else
                                    Role has assigned users.
                                @endif
                            </small>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-shield-alt"></i> Super Admin role is protected and cannot be modified or deleted.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
