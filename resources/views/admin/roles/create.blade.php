@extends('layouts.dashboard')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Create New Role</h2>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Role Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tenant_id">Tenant</label>
                                    <select name="tenant_id" id="tenant_id" class="form-control">
                                        <option value="">Platform (No Tenant)</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                                                                {{ $tenant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Leave empty for platform-level roles</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority">Priority *</label>
                                    <input type="number" name="priority" id="priority" class="form-control" 
                                           value="{{ old('priority', 50) }}" min="1" max="100" required>
                                    <small class="form-text text-muted">Lower = Higher priority</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_system">System Role</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" name="is_system" id="is_system" class="custom-control-input" 
                                               value="1" {{ old('is_system') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_system">Mark as system role</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Assign Permissions</h5>
                        <div class="permissions-grid">
                            @foreach($permissions as $resource => $perms)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $resource)) }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($perms as $permission)
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                               id="perm_{{ $permission->id }}" class="custom-control-input"
                                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                            @if($permission->description)
                                                                <br><small class="text-muted">{{ $permission->description }}</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Create Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Role Guidelines</strong>
                </div>
                <div class="card-body">
                    <h6>Priority Levels:</h6>
                    <ul class="small">
                        <li><strong>1-10:</strong> Platform Administrators</li>
                        <li><strong>11-30:</strong> Tenant Administrators</li>
                        <li><strong>31-70:</strong> Managers & Staff</li>
                        <li><strong>71-100:</strong> Users & Customers</li>
                    </ul>
                    <hr>
                    <h6>System Roles:</h6>
                    <p class="small">System roles cannot be deleted and are typically pre-defined by the platform.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.permissions-grid .card { border-left: 3px solid #007bff; }
.permissions-grid .custom-control { margin-bottom: 10px; }
</style>
@endpush
