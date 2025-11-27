@extends('layouts.dashboard')

@section('title', 'Roles & Permissions Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Roles & Permissions</h2>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Role
                </a>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.roles.index') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="tenant_id" class="mr-2">Tenant:</label>
                            <select name="tenant_id" id="tenant_id" class="form-control">
                                <option value="">All Tenants</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="search" class="mr-2">Search:</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="form-control" placeholder="Role name...">
                        </div>
                        <button type="submit" class="btn btn-secondary mr-2">Filter</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Clear</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Tenant</th>
                                    <th>Priority</th>
                                    <th>Users</th>
                                    <th>System Role</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                            @if($role->description)
                                                <br><small class="text-muted">{{ $role->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($role->tenant)
                                                <span class="badge badge-info">{{ $role->tenant->name }}</span>
                                            @else
                                                <span class="badge badge-secondary">Platform</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $role->priority <= 10 ? 'danger' : ($role->priority <= 50 ? 'warning' : 'secondary') }}">
                                                {{ $role->priority }}
                                            </span>
                                        </td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>
                                            @if($role->is_system)
                                                <i class="fas fa-shield-alt text-primary"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!($role->is_system && $role->name === 'Super Admin'))
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($role->users_count == 0 && !$role->is_system)
                                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No roles found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge { margin-right: 4px; }
</style>
@endpush
