@extends('layouts.dashboard')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Manage Users') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                    {{ __('Create New User') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchInput" placeholder="{{ __('Search users...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" id="roleFilter">
                                <option value="">{{ __('All Roles') }}</option>
                                <option value="Super Admin">{{ __('Super Admin') }}</option>
                                <option value="Tenant Admin">{{ __('Tenant Admin') }}</option>
                                <option value="Provider">{{ __('Provider') }}</option>
                                <option value="Customer">{{ __('Customer') }}</option>
                                <option value="Operations">{{ __('Operations') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @foreach($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->profile ? $user->profile->getFullNameAttribute() : 'N/A' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->tenant ? $user->tenant->name : 'N/A' }}</td>
                                    <td>
                                        @if($user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-info">{{ $role->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-secondary">{{ __('No Role') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @elseif($user->status === 'inactive')
                                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($user->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-user" data-user-id="{{ $user->id }}">
                                            {{ __('View') }}
                                        </button>
                                        <button class="btn btn-sm btn-warning edit-user" data-user-id="{{ $user->id }}">
                                            {{ __('Edit') }}
                                        </button>
                                        @if($user->status === 'active')
                                            <button class="btn btn-sm btn-danger deactivate-user" data-user-id="{{ $user->id }}">
                                                {{ __('Deactivate') }}
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-success activate-user" data-user-id="{{ $user->id }}">
                                                {{ __('Activate') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        </div>
                        <nav>
                            {{ $users->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New User') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createUserForm">
                    <div class="form-group">
                        <label for="userName">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="userName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" id="userEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">{{ __('Password') }}</label>
                        <input type="password" class="form-control" id="userPassword" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="userTenant">{{ __('Tenant') }}</label>
                        <select class="form-control" id="userTenant" name="tenant_id">
                            <option value="">{{ __('Select Tenant') }}</option>
                            <!-- Tenants will be populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userRole">{{ __('Role') }}</label>
                        <select class="form-control" id="userRole" name="role">
                            <option value="">{{ __('Select Role') }}</option>
                            <option value="Super Admin">{{ __('Super Admin') }}</option>
                            <option value="Tenant Admin">{{ __('Tenant Admin') }}</option>
                            <option value="Provider">{{ __('Provider') }}</option>
                            <option value="Customer">{{ __('Customer') }}</option>
                            <option value="Operations">{{ __('Operations') }}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">{{ __('Create User') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit User') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="form-group">
                        <label for="editUserName">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserTenant">{{ __('Tenant') }}</label>
                        <select class="form-control" id="editUserTenant" name="tenant_id">
                            <option value="">{{ __('Select Tenant') }}</option>
                            <!-- Tenants will be populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUserRole">{{ __('Role') }}</label>
                        <select class="form-control" id="editUserRole" name="role">
                            <option value="">{{ __('Select Role') }}</option>
                            <option value="Super Admin">{{ __('Super Admin') }}</option>
                            <option value="Tenant Admin">{{ __('Tenant Admin') }}</option>
                            <option value="Provider">{{ __('Provider') }}</option>
                            <option value="Customer">{{ __('Customer') }}</option>
                            <option value="Operations">{{ __('Operations') }}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="updateUserBtn">{{ __('Update User') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('User Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userDetails">
                    <!-- User details will be populated dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for dynamic functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Set up AJAX headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsers();
        }, 500);
    });
    
    // Role filter functionality
    const roleFilter = document.getElementById('roleFilter');
    roleFilter.addEventListener('change', function() {
        loadUsers();
    });
    
    // Load users with filters
    function loadUsers() {
        const search = searchInput.value;
        const role = roleFilter.value;
        
        // In a real implementation, this would make an AJAX call to fetch filtered users
        console.log('Loading users with search: ' + search + ' and role: ' + role);
    }
    
    // View user button click handler
    document.querySelectorAll('.view-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            viewUser(userId);
        });
    });
    
    // Edit user button click handler
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            editUser(userId);
        });
    });
    
    // Activate user button click handler
    document.querySelectorAll('.activate-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            activateUser(userId);
        });
    });
    
    // Deactivate user button click handler
    document.querySelectorAll('.deactivate-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            deactivateUser(userId);
        });
    });
    
    // Save user button click handler
    document.getElementById('saveUserBtn').addEventListener('click', function() {
        createUser();
    });
    
    // Update user button click handler
    document.getElementById('updateUserBtn').addEventListener('click', function() {
        updateUser();
    });
    
    // View user function
    function viewUser(userId) {
        $.ajax({
            url: '/api/v1/superadmin/users/' + userId,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const user = response.data;
                    let userDetails = '<div class="row">';
                    userDetails += '<div class="col-md-6">';
                    userDetails += '<p><strong>Name:</strong> ' + (user.profile ? user.profile.first_name + ' ' + user.profile.last_name : 'N/A') + '</p>';
                    userDetails += '<p><strong>Email:</strong> ' + user.email + '</p>';
                    userDetails += '<p><strong>Status:</strong> ' + user.status + '</p>';
                    userDetails += '</div>';
                    userDetails += '<div class="col-md-6">';
                    userDetails += '<p><strong>Tenant:</strong> ' + (user.tenant ? user.tenant.name : 'N/A') + '</p>';
                    userDetails += '<p><strong>Role:</strong> ' + (user.roles.length > 0 ? user.roles[0].name : 'N/A') + '</p>';
                    userDetails += '<p><strong>Created:</strong> ' + new Date(user.created_at).toLocaleString() + '</p>';
                    userDetails += '</div>';
                    userDetails += '</div>';
                    
                    document.getElementById('userDetails').innerHTML = userDetails;
                    $('#viewUserModal').modal('show');
                }
            },
            error: function(xhr) {
                alert('Error loading user details');
            }
        });
    }
    
    // Edit user function
    function editUser(userId) {
        $.ajax({
            url: '/api/v1/superadmin/users/' + userId,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const user = response.data;
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUserName').value = user.profile ? user.profile.first_name + ' ' + user.profile.last_name : '';
                    document.getElementById('editUserEmail').value = user.email;
                    document.getElementById('editUserTenant').value = user.tenant_id || '';
                    document.getElementById('editUserRole').value = user.roles.length > 0 ? user.roles[0].name : '';
                    $('#editUserModal').modal('show');
                }
            },
            error: function(xhr) {
                alert('Error loading user details');
            }
        });
    }
    
    // Create user function
    function createUser() {
        const formData = {
            name: document.getElementById('userName').value,
            email: document.getElementById('userEmail').value,
            password: document.getElementById('userPassword').value,
            tenant_id: document.getElementById('userTenant').value,
            role: document.getElementById('userRole').value
        };
        
        // Split name into first and last name
        const nameParts = formData.name.split(' ');
        const firstName = nameParts[0];
        const lastName = nameParts.slice(1).join(' ');
        
        $.ajax({
            url: '/api/v1/superadmin/users',
            method: 'POST',
            data: {
                email: formData.email,
                password: formData.password,
                first_name: firstName,
                last_name: lastName,
                tenant_id: formData.tenant_id,
                role: formData.role
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#createUserModal').modal('hide');
                    alert('User created successfully');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Error creating user');
            }
        });
    }
    
    // Update user function
    function updateUser() {
        const userId = document.getElementById('editUserId').value;
        const formData = {
            name: document.getElementById('editUserName').value,
            email: document.getElementById('editUserEmail').value,
            tenant_id: document.getElementById('editUserTenant').value,
            role: document.getElementById('editUserRole').value
        };
        
        // Split name into first and last name
        const nameParts = formData.name.split(' ');
        const firstName = nameParts[0];
        const lastName = nameParts.slice(1).join(' ');
        
        $.ajax({
            url: '/api/v1/superadmin/users/' + userId,
            method: 'PUT',
            data: {
                email: formData.email,
                first_name: firstName,
                last_name: lastName,
                tenant_id: formData.tenant_id,
                role: formData.role
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#editUserModal').modal('hide');
                    alert('User updated successfully');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Error updating user');
            }
        });
    }
    
    // Activate user function
    function activateUser(userId) {
        if (confirm('Are you sure you want to activate this user?')) {
            $.ajax({
                url: '/api/v1/superadmin/users/' + userId + '/activate',
                method: 'POST',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('User activated successfully');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error activating user');
                }
            });
        }
    }
    
    // Deactivate user function
    function deactivateUser(userId) {
        if (confirm('Are you sure you want to deactivate this user?')) {
            $.ajax({
                url: '/api/v1/superadmin/users/' + userId + '/deactivate',
                method: 'POST',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('User deactivated successfully');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error deactivating user');
                }
            });
        }
    }
});
</script>
@endsection