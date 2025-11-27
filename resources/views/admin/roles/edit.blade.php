@extends('layouts.dashboard')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Edit Role: {{ $role->name }}</h2>
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
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Role Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $role->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $role->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tenant_id">Tenant</label>
                                    <select name="tenant_id" id="tenant_id" class="form-control">
                                        <option value="">Platform (No Tenant)</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" 
                                                {{ old('tenant_id', $role->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                                {{ $tenant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority">Priority *</label>
                                    <input type="number" name="priority" id="priority" class="form-control" 
                                           value="{{ old('priority', $role->priority) }}" min="1" max="100" required>
                                            <small class="form-text text-muted">Lower = Higher priority</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_system">System Role</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" name="is_system" id="is_system" class="custom-control-input" 
                                               value="1" {{ old('is_system', $role->is_system) ? 'checked' : '' }}>
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
                                                               {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Update Role
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
                    <strong>Role Info</strong>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong> {{ $role->created_at->format('M d, Y') }}</p>
                    <p><strong>Updated:</strong> {{ $role->updated_at->format('M d, Y') }}</p>
                    <p><strong>Users:</strong> {{ $role->users()->count() }}</p>
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
