@extends('dashboard.layout')

@section('title', 'Create User')

@section('content')
<div class="users-page">
    <div class="ms-container">
        
        {{-- Page Header --}}
        <div class="ms-page-header">
            <h1 class="ms-page-title">➕ Create New User</h1>
            <a href="{{ route('admin.users.index') }}" class="ms-button ms-button-secondary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Users
            </a>
        </div>

        <div class="row">
            {{-- Main Form --}}
            <div class="col-lg-8">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Account Information Card --}}
                    <div class="ms-card">
                        <div class="ms-card-header">
                            <h2 class="ms-card-title">📧 Account Information</h2>
                        </div>
                        <div style="padding: 2rem;">
                            {{-- Email --}}
                            <div class="ms-form-field mb-4">
                                <label for="email" class="ms-label">Email Address <span style="color: var(--ms-danger);">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="ms-input @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="user@example.com">
                                @error('email')
                                    <span style="color: var(--ms-danger); font-size: 0.8125rem; margin-top: 0.375rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password Fields --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="ms-form-field mb-4">
                                        <label for="password" class="ms-label">Password <span style="color: var(--ms-danger);">*</span></label>
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="ms-input @error('password') is-invalid @enderror" 
                                               required
                                               placeholder="Enter password">
                                        @error('password')
                                            <span style="color: var(--ms-danger); font-size: 0.8125rem; margin-top: 0.375rem;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ms-form-field mb-4">
                                        <label for="password_confirmation" class="ms-label">Confirm Password <span style="color: var(--ms-danger);">*</span></label>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="ms-input" 
                                               required
                                               placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Organization & Status Card --}}
                    <div class="ms-card">
                        <div class="ms-card-header">
                            <h2 class="ms-card-title">🏢 Organization & Status</h2>
                        </div>
                        <div style="padding: 2rem;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="ms-form-field mb-4">
                                        <label for="tenant_id" class="ms-label">Tenant <span style="color: var(--ms-danger);">*</span></label>
                                        <select name="tenant_id" id="tenant_id" class="ms-select @error('tenant_id') is-invalid @enderror" required>
                                            <option value="">Select Tenant...</option>
                                            @foreach($tenants as $tenant)
                                                <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                                    {{ $tenant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tenant_id')
                                            <span style="color: var(--ms-danger); font-size: 0.8125rem; margin-top: 0.375rem;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="ms-form-field mb-4">
                                        <label for="status" class="ms-label">Status <span style="color: var(--ms-danger);">*</span></label>
                                        <select name="status" id="status" class="ms-select" required>
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✓ Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>○ Inactive</option>
                                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>⊘ Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Roles Card --}}
                    <div class="ms-card">
                        <div class="ms-card-header">
                            <h2 class="ms-card-title">🔐 Assign Roles</h2>
                            <span style="font-size: 0.875rem; color: var(--ms-gray-300);">Select one or more roles</span>
                        </div>
                        <div style="padding: 2rem;">
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-6 mb-3">
                                        <div style="padding: 1rem; background: var(--ms-gray-50); border-radius: 8px; border: 2px solid transparent; transition: all 0.2s ease;">
                                            <label style="display: flex; align-items: start; gap: 0.75rem; cursor: pointer; margin: 0;">
                                                <input type="checkbox" 
                                                       name="roles[]" 
                                                       value="{{ $role->id }}" 
                                                       id="role_{{ $role->id }}"
                                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                                       style="margin-top: 0.25rem;">
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600; color: var(--ms-gray-900); margin-bottom: 0.25rem;">
                                                        {{ $role->name }}
                                                        <span class="ms-badge ms-badge-{{ $role->priority <= 10 ? 'danger' : 'gray' }}" style="margin-left: 0.5rem;">
                                                            P{{ $role->priority }}
                                                        </span>
                                                    </div>
                                                    @if($role->description)
                                                        <div style="font-size: 0.8125rem; color: var(--ms-gray-300);">
                                                            {{ $role->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <button type="submit" class="ms-button ms-button-primary">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M13.5 4L6 11.5 2.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="ms-button ms-button-secondary">Cancel</a>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Password Guidelines --}}
                <div class="ms-card">
                    <div class="ms-card-header">
                        <h2 class="ms-card-title">🔒 Password Requirements</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <ul style="margin: 0; padding-left: 1.25rem; color: var(--ms-gray-900); font-size: 0.875rem; line-height: 1.75;">
                            <li>Minimum 8 characters</li>
                            <li>Should include letters and numbers</li>
                            <li>Must match confirmation field</li>
                        </ul>
                    </div>
                </div>

                {{-- Status Information --}}
                <div class="ms-card">
                    <div class="ms-card-header">
                        <h2 class="ms-card-title">ℹ️ Status Information</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.375rem;">
                                <span class="ms-badge ms-badge-success">Active</span>
                            </div>
                            <p style="margin: 0; font-size: 0.8125rem; color: var(--ms-gray-300); padding-left: 0.25rem;">
                                User can login and access all assigned resources
                            </p>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.375rem;">
                                <span class="ms-badge ms-badge-gray">Inactive</span>
                            </div>
                            <p style="margin: 0; font-size: 0.8125rem; color: var(--ms-gray-300); padding-left: 0.25rem;">
                                User cannot login to the system
                            </p>
                        </div>

                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.375rem;">
                                <span class="ms-badge ms-badge-danger">Suspended</span>
                            </div>
                            <p style="margin: 0; font-size: 0.8125rem; color: var(--ms-gray-300); padding-left: 0.25rem;">
                                Account temporarily blocked by administrator
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Quick Tips --}}
                <div class="ms-card">
                    <div class="ms-card-header">
                        <h2 class="ms-card-title">💡 Quick Tips</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <ul style="margin: 0; padding-left: 1.25rem; color: var(--ms-gray-900); font-size: 0.875rem; line-height: 1.75;">
                            <li>Users will receive a welcome email</li>
                            <li>Multiple roles can be assigned</li>
                            <li>Roles determine access permissions</li>
                            <li>You can edit user details later</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Role checkbox hover effect */
input[type="checkbox"]:checked + div {
    background: var(--ms-blue-light) !important;
    border-color: var(--ms-blue) !important;
}

label:has(input[type="checkbox"]) {
    margin: 0 !important;
}

label:has(input[type="checkbox"]):hover > div:first-of-type {
    border-color: var(--ms-blue) !important;
    background: var(--ms-blue-light) !important;
}
</style>
@endsection
