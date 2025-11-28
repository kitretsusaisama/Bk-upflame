@extends('dashboard.layout')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">User: {{ $user->email }}</h2>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {{-- User Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>User Information</strong>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Email:</th>
                            <td>
                                {{ $user->email }}
                                @if($user->email_verified)
                                    <i class="fas fa-check-circle text-success" title="Email Verified"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger" title="Email Not Verified"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tenant:</th>
                            <td>
                                @if($user->tenant)
                                    <span class="badge badge-info">{{ $user->tenant->name }}</span>
                                @else
                                    <span class="text-muted">No tenant assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($user->status === 'inactive')
                                    <span class="badge badge-secondary">Inactive</span>
                                @else
                                    <span class="badge badge-danger">Suspended</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Last Login:</th>
                            <td>
                                @if($user->last_login)
                                    {{ $user->last_login->format('M d, Y H:i') }}
                                    <small class="text-muted">({{ $user->last_login->diffForHumans() }})</small>
                                @else
                                    <span class="text-muted">Never logged in</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>MFA Enabled:</th>
                            <td>
                                @if($user->mfa_enabled)
                                    <i class="fas fa-check-circle text-success"></i> Yes
                                @else
                                    <i class="fas fa-times-circle text-muted"></i> No
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Assigned Roles --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Assigned Roles ({{ $user->roles->count() }})</strong>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Priority</th>
                                        <th>System Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->roles as $role)
                                        <tr>
                                            <td>
                                                <strong>{{ $role->name }}</strong>
                                                @if($role->description)
                                                    <br><small class="text-muted">{{ $role->description }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $role->priority <= 10 ? 'danger' : 'secondary' }}">
                                                    {{ $role->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($role->is_system)
                                                    <i class="fas fa-shield-alt text-primary"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No roles assigned to this user.</p>
                    @endif
                </div>
            </div>

            {{-- Active Sessions --}}
            <div class="card">
                <div class="card-header">
                    <strong>Active Sessions ({{ $user->sessions->count() }})</strong>
                </div>
                <div class="card-body">
                    @if($user->sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Device</th>
                                        <th>IP Address</th>
                                        <th>Last Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->sessions as $session)
                                        <tr>
                                            <td>
                                                @if($session->device_type)
                                                    <i class="fas fa-{{ $session->device_type == 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                                                @endif
                                                {{ $session->user_agent ?? 'Unknown' }}
                                            </td>
                                            <td>{{ $session->ip_address ?? '-' }}</td>
                                            <td>{{ $session->last_activity ? \Carbon\Carbon::parse($session->last_activity)->diffForHumans() : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No active sessions.</p>
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
                        <h4 class="mb-0">{{ $user->roles->count() }}</h4>
                        <small class="text-muted">Roles</small>
                    </div>
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $user->sessions->count() }}</h4>
                        <small class="text-muted">Active Sessions</small>
                    </div>
                    @if($user->last_login)
                        <div class="mb-3">
                            <h6 class="mb-0">{{ $user->last_login->diffForHumans() }}</h6>
                            <small class="text-muted">Last Login</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <strong>Actions</strong>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit User
                    </a>

                    @if($user->status === 'active')
                        <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-block">
                                <i class="fas fa-ban"></i> Deactivate Account
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Activate Account
                            </button>
                        </form>
                    @endif

                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Delete User
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-lock"></i> Cannot Delete Self
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
