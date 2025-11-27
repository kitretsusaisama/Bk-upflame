@extends('layouts.dashboard')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Create New User</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password *</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password *</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tenant_id">Tenant *</label>
                                    <select name="tenant_id" id="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror" required>
                                        <option value="">Select Tenant...</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                                {{ $tenant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Assign Roles</h5>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                               id="role_{{ $role->id }}" class="custom-control-input"
                                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                            <span class="badge badge-sm badge-{{ $role->priority <= 10 ? 'danger' : 'secondary' }}">
                                                P{{ $role->priority }}
                                            </span>
                                            @if($role->description)
                                                <br><small class="text-muted">{{ $role->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>User Guidelines</strong>
                </div>
                <div class="card-body">
                    <h6>Password Requirements:</h6>
                    <ul class="small">
                        <li>Minimum 8 characters</li>
                        <li>Should include letters and numbers</li>
                    </ul>
                    <hr>
                    <h6>Status Options:</h6>
                    <ul class="small">
                        <li><strong>Active:</strong> User can login normally</li>
                        <li><strong>Inactive:</strong> User cannot login</li>
                        <li><strong>Suspended:</strong> Account temporarily blocked</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
