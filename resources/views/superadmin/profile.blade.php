@extends('layouts.dashboard')

@section('title', 'Super Admin Profile')

@section('content')
<div class="grid grid-1">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Profile Settings</h4>
        </div>
        <div class="card-body">
            <div class="grid grid-2" style="gap: 2rem;">
                <div>
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($user->email ?? 'U', 0, 1)) }}
                        </div>
                        <h5>{{ $user->email ?? 'User' }}</h5>
                        <p class="text-muted">{{ $userRole }}</p>
                    </div>
                    
                    <div class="nav flex-column" id="profileTabs" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" data-tab="profileInfo" href="#profileInfo">
                            <i class="ti ti-user me-2"></i>Profile Information
                        </a>
                        <a class="nav-link" data-tab="security" href="#security">
                            <i class="ti ti-lock me-2"></i>Security
                        </a>
                        <a class="nav-link" data-tab="preferences" href="#preferences">
                            <i class="ti ti-settings me-2"></i>Preferences
                        </a>
                    </div>
                </div>
                
                <div>
                    <div class="tab-content" id="profileTabContent">
                        <!-- Profile Information Tab -->
                        <div class="tab-pane active" id="profileInfo" role="tabpanel">
                            <h5>Profile Information</h5>
                            <p class="text-muted">Update your profile information and email address.</p>
                            
                            <form method="POST" action="{{ route('superadmin.profile') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->profile ? $user->profile->full_name : '' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->profile->phone ?? '' }}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                        
                        <!-- Security Tab -->
                        <div class="tab-pane" id="security" role="tabpanel" style="display: none;">
                            <h5>Security Settings</h5>
                            <p class="text-muted">Manage your password and security preferences.</p>
                            
                            <form method="POST" action="{{ route('superadmin.profile') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="newPassword_confirmation">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                        
                        <!-- Preferences Tab -->
                        <div class="tab-pane" id="preferences" role="tabpanel" style="display: none;">
                            <h5>Preferences</h5>
                            <p class="text-muted">Customize your dashboard experience.</p>
                            
                            <form method="POST" action="{{ route('superadmin.profile') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="UTC" {{ (isset($user->timezone) && $user->timezone == 'UTC') ? 'selected' : (old('timezone') == 'UTC' ? 'selected' : '') }}>UTC</option>
                                        <option value="America/New_York" {{ (isset($user->timezone) && $user->timezone == 'America/New_York') ? 'selected' : (old('timezone') == 'America/New_York' ? 'selected' : '') }}>Eastern Time</option>
                                        <option value="America/Chicago" {{ (isset($user->timezone) && $user->timezone == 'America/Chicago') ? 'selected' : (old('timezone') == 'America/Chicago' ? 'selected' : '') }}>Central Time</option>
                                        <option value="America/Denver" {{ (isset($user->timezone) && $user->timezone == 'America/Denver') ? 'selected' : (old('timezone') == 'America/Denver' ? 'selected' : '') }}>Mountain Time</option>
                                        <option value="America/Los_Angeles" {{ (isset($user->timezone) && $user->timezone == 'America/Los_Angeles') ? 'selected' : (old('timezone') == 'America/Los_Angeles' ? 'selected' : '') }}>Pacific Time</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="emailNotifications" name="emailNotifications" {{ (isset($user->email_notifications) && $user->email_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="darkMode" name="darkMode" {{ (isset($user->dark_mode) && $user->dark_mode) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="darkMode">Dark Mode</label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Preferences</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        color: var(--gray-700);
        text-decoration: none;
        border-radius: var(--radius-md);
        transition: var(--transition);
        margin-bottom: 0.25rem;
    }
    
    .nav-link:hover {
        background: var(--gray-100);
        color: var(--primary);
    }
    
    .nav-link.active {
        background: var(--primary);
        color: white;
    }
    
    .form-control {
        display: block;
        width: 100%;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--gray-900);
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-md);
        transition: var(--transition);
    }
    
    .form-control:focus {
        color: var(--gray-900);
        background-color: #fff;
        border-color: var(--primary);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-check-input {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: var(--primary);
    }
    
    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabLinks = document.querySelectorAll('[data-tab]');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links and panes
                tabLinks.forEach(l => l.classList.remove('active'));
                tabPanes.forEach(p => p.style.display = 'none');
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding pane
                const target = this.getAttribute('data-tab');
                document.getElementById(target).style.display = 'block';
            });
        });
    });
</script>
@endsection