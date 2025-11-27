@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Edit User: {{ $user->email }}</h2>
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
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New Password (leave blank to keep current)</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
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
                                            <option value="{{ $tenant->id }}" {{ old('tenant_id', $user->tenant_id) == $tenant->id ? 'selected' : '' }}>
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
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
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
                                               {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <strong>User Info</strong>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Login:</strong> 
                        @if($user->last_login)
                            {{ $user->last_login->format('M d, Y H:i') }}
                        @else
                            <span class="text-muted">Never</span>
                        @endif
                    </p>
                    <p><strong>Email Verified:</strong> 
                        @if($user->email_verified)
                            <i class="fas fa-check-circle text-success"></i> Yes
                        @else
                            <i class="fas fa-times-circle text-danger"></i> No
                        @endif
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong>Quick Actions</strong>
                </div>
                <div class="card-body">
                    @if($user->status === 'active')
                        <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">Deactivate Account</button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">Activate Account</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
