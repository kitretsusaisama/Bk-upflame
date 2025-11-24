<div x-data="userManagement()" class="user-management-container">
    <!-- Header -->
    <div class="page-header mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="page-title">User Management</h1>
                <p class="text-muted mt-1">Manage users, roles, and permissions</p>
            </div>
            <x-button @click="openCreateModal" variant="primary" icon="ti ti-plus">
                Create User
            </x-button>
        </div>
    </div>

    <!-- Filters -->
    <x-card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Search</label>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Search by name or email..."
                    x-model.debounce.300ms="filters.search"
                    @input="loadUsers()"
                >
            </div>
            <div>
                <label class="form-label">Role</label>
                <select class="form-control" x-model="filters.role" @change="loadUsers()">
                    <option value="">All Roles</option>
                    <template x-for="role in roles" :key="role.id">
                        <option :value="role.name" x-text="role.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select class="form-control" x-model="filters.status" @change="loadUsers()">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>
    </x-card>

    <!-- Users Table -->
    <x-card>
        <div class="p-0">
            <!-- Loading State -->
            <template x-if="loading">
                <div class="p-6">
                    <div class="space-y-4">
                        <template x-for="i in 5" :key="i">
                            <div class="flex items-center space-x-4 p-4">
                                <div class="rounded-full bg-gray-200 h-10 w-10 animate-pulse"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                                </div>
                                <div class="h-6 bg-gray-200 rounded w-16 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 rounded w-16 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 rounded w-16 animate-pulse"></div>
                                <div class="h-8 bg-gray-200 rounded w-20 animate-pulse"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && users.length === 0">
                <div class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
                        <i class="ti ti-users text-2xl text-blue-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Get started by creating a new user. You can also adjust your search or filter criteria to find what you're looking for.</p>
                    <div class="flex justify-center gap-3">
                        <x-button @click="openCreateModal" variant="primary" icon="ti ti-plus">
                            Create User
                        </x-button>
                        <x-button @click="resetFilters" variant="outline">
                            Reset Filters
                        </x-button>
                    </div>
                </div>
            </template>

            <!-- Users Table -->
            <template x-if="!loading && users.length > 0">
                <div>
                    <!-- Desktop Table -->
                    <div class="table-desktop overflow-x-auto">
                        <table class="table w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="user in users" :key="user.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <x-avatar :name="user.profile ? (user.profile.first_name + ' ' + user.profile.last_name) : user.email" size="md" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900" 
                                                         x-text="user.profile ? (user.profile.first_name + ' ' + user.profile.last_name) : 'N/A'">
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="user.email"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template x-if="user.roles && user.roles.length > 0">
                                                <x-badge variant="primary" x-text="user.roles[0].name"></x-badge>
                                            </template>
                                            <template x-if="!user.roles || user.roles.length === 0">
                                                <x-badge variant="secondary">No Role</x-badge>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span x-text="user.tenant ? user.tenant.name : 'N/A'"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template x-if="user.status === 'active'">
                                                <x-badge variant="success">Active</x-badge>
                                            </template>
                                            <template x-if="user.status === 'inactive'">
                                                <x-badge variant="warning">Inactive</x-badge>
                                            </template>
                                            <template x-if="user.status === 'suspended'">
                                                <x-badge variant="error">Suspended</x-badge>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <x-button @click="viewUser(user)" variant="outline" size="sm" icon="ti ti-eye" />
                                                <x-button @click="editUser(user)" variant="outline" size="sm" icon="ti ti-edit" />
                                                <div class="dropdown">
                                                    <x-button variant="outline" size="sm" icon="ti ti-dots-vertical" />
                                                    <div class="dropdown-menu">
                                                        <template x-if="user.status === 'active'">
                                                            <button @click="deactivateUser(user)" class="dropdown-item text-warning">
                                                                <i class="ti ti-user-off mr-2"></i>
                                                                Deactivate
                                                            </button>
                                                        </template>
                                                        <template x-if="user.status !== 'active'">
                                                            <button @click="activateUser(user)" class="dropdown-item text-success">
                                                                <i class="ti ti-user-check mr-2"></i>
                                                                Activate
                                                            </button>
                                                        </template>
                                                        <button @click="deleteUser(user)" class="dropdown-item text-error">
                                                            <i class="ti ti-trash mr-2"></i>
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Cards -->
                    <div class="table-mobile space-y-4">
                        <template x-for="user in users" :key="user.id">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <x-avatar :name="user.profile ? (user.profile.first_name + ' ' + user.profile.last_name) : user.email" size="md" />
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900" 
                                                 x-text="user.profile ? (user.profile.first_name + ' ' + user.profile.last_name) : 'N/A'">
                                            </div>
                                            <div class="text-sm text-gray-500" x-text="user.email"></div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-1">
                                        <x-button @click="viewUser(user)" variant="outline" size="sm" icon="ti ti-eye" />
                                        <x-button @click="editUser(user)" variant="outline" size="sm" icon="ti ti-edit" />
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-500">Role:</span>
                                        <div class="mt-1">
                                            <template x-if="user.roles && user.roles.length > 0">
                                                <x-badge variant="primary" x-text="user.roles[0].name"></x-badge>
                                            </template>
                                            <template x-if="!user.roles || user.roles.length === 0">
                                                <x-badge variant="secondary">No Role</x-badge>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Tenant:</span>
                                        <div class="mt-1 text-gray-900" x-text="user.tenant ? user.tenant.name : 'N/A'"></div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Status:</span>
                                        <div class="mt-1">
                                            <template x-if="user.status === 'active'">
                                                <x-badge variant="success">Active</x-badge>
                                            </template>
                                            <template x-if="user.status === 'inactive'">
                                                <x-badge variant="warning">Inactive</x-badge>
                                            </template>
                                            <template x-if="user.status === 'suspended'">
                                                <x-badge variant="error">Suspended</x-badge>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Actions:</span>
                                        <div class="mt-1 flex space-x-1">
                                            <template x-if="user.status === 'active'">
                                                <x-button @click="deactivateUser(user)" variant="outline" size="sm" icon="ti ti-user-off" />
                                            </template>
                                            <template x-if="user.status !== 'active'">
                                                <x-button @click="activateUser(user)" variant="outline" size="sm" icon="ti ti-user-check" />
                                            </template>
                                            <x-button @click="deleteUser(user)" variant="outline" size="sm" icon="ti ti-trash" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <template x-if="!loading && pagination && users.length > 0">
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <span x-text="pagination.from"></span> to <span x-text="pagination.to"></span> of <span x-text="pagination.total"></span> results
                    </div>
                    <div class="flex space-x-2">
                       <x-button 
                            @click="loadUsers(pagination.prev_page_url)" 
                            :disabled="!pagination.prev_page_url"
                            variant="outline"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !pagination.prev_page_url }">
                            Previous
                        </x-button>

                        <x-button 
                            @click="loadUsers(pagination.next_page_url)" 
                            :disabled="!pagination.next_page_url"
                            variant="outline"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !pagination.next_page_url }">
                            Next
                        </x-button>
                    </div>
                </div>
            </div>
        </template>
    </x-card>

    <!-- User Modal -->
    <div x-show="showModal" class="modal" x-transition>
        <div class="modal-overlay" @click="closeModal"></div>
        <div class="modal-container" @click.outside="closeModal">
            <div class="modal-header">
                <h3 class="modal-title" x-text="modalTitle"></h3>
                <button @click="closeModal" class="modal-close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <template x-if="modalMode === 'view'">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                <x-avatar :name="selectedUser.profile ? (selectedUser.profile.first_name + ' ' + selectedUser.profile.last_name) : selectedUser.email" size="xl" />
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium" 
                                    x-text="selectedUser.profile ? (selectedUser.profile.first_name + ' ' + selectedUser.profile.last_name) : 'N/A'">
                                </h4>
                                <p class="text-gray-500" x-text="selectedUser.email"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p class="mt-1" x-text="selectedUser.status"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Role</label>
                                <p class="mt-1" x-text="selectedUser.roles && selectedUser.roles.length > 0 ? selectedUser.roles[0].name : 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tenant</label>
                                <p class="mt-1" x-text="selectedUser.tenant ? selectedUser.tenant.name : 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Created</label>
                                <p class="mt-1" x-text="new Date(selectedUser.created_at).toLocaleDateString()"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="modalMode === 'create' || modalMode === 'edit'">
                    <form @submit.prevent="saveUser">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Full Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    x-model="userForm.name"
                                    :disabled="modalMode === 'view'"
                                    required
                                >
                            </div>
                            <div>
                                <label class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    x-model="userForm.email"
                                    :disabled="modalMode === 'view'"
                                    required
                                >
                            </div>
                            <template x-if="modalMode === 'create'">
                                <div>
                                    <label class="form-label">Password</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        x-model="userForm.password"
                                        :disabled="modalMode === 'view'"
                                        required
                                    >
                                </div>
                            </template>
                            <div>
                                <label class="form-label">Tenant</label>
                                <select class="form-control" x-model="userForm.tenant_id" :disabled="modalMode === 'view'">
                                    <option value="">Select Tenant</option>
                                    <template x-for="tenant in tenants" :key="tenant.id">
                                        <option :value="tenant.id" x-text="tenant.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Role</label>
                                <select class="form-control" x-model="userForm.role" :disabled="modalMode === 'view'">
                                    <option value="">Select Role</option>
                                    <template x-for="role in roles" :key="role.id">
                                        <option :value="role.name" x-text="role.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </form>
                </template>
            </div>
            <div class="modal-footer">
                <template x-if="modalMode === 'view'">
                    <x-button @click="closeModal" variant="outline">Close</x-button>
                </template>
                <template x-if="modalMode === 'create' || modalMode === 'edit'">
                    <div class="flex space-x-2">
                        <x-button @click="closeModal" variant="outline">Cancel</x-button>
                        <x-button @click="saveUser" variant="primary" :disabled="saving">
                            <template x-if="saving">
                                <span class="spinner spinner-sm mr-2"></span>
                            </template>
                            <span x-text="modalMode === 'create' ? 'Create User' : 'Update User'"></span>
                        </x-button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container">
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                class="toast" 
                :class="`toast-${toast.type}`"
                x-show="toast.visible"
                x-transition
            >
                <div class="toast-content">
                    <i class="toast-icon" :class="toast.icon"></i>
                    <span x-text="toast.message"></span>
                </div>
                <button @click="removeToast(toast.id)" class="toast-close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </template>
    </div>
</div>

<style>
.user-management-container {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --radius: 0.5rem;
    --spacing: 1rem;
}

.page-header {
    margin-bottom: 1.5rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
}

.text-muted {
    color: var(--gray-500);
}

.card {
    background: white;
    border-radius: var(--radius);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-200);
}

.card-body {
    padding: 1.5rem;
}

.card-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--gray-200);
    background: var(--gray-50);
}

.table {
    border-collapse: collapse;
}

.table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
}

.table td {
    padding: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--gray-700);
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.modal-container {
    position: relative;
    background: white;
    border-radius: var(--radius);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    max-width: 32rem;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--gray-500);
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1001;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.toast {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: var(--radius);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    min-width: 300px;
    animation: slideIn 0.3s ease-out;
    font-weight: 500;
}

.toast-success {
    background: var(--success);
    color: white;
    border-left: 4px solid #047857;
}

.toast-error {
    background: var(--error);
    color: white;
    border-left: 4px solid #b91c1c;
}

.toast-warning {
    background: var(--warning);
    color: white;
    border-left: 4px solid #b45309;
}

.toast-info {
    background: var(--primary);
    color: white;
    border-left: 4px solid #1d4ed8;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.toast-icon {
    font-size: 1.25rem;
}

.toast-close {
    background: none;
    border: none;
    color: inherit;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0.8;
    padding: 0.25rem;
    border-radius: 0.25rem;
}

.toast-close:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.2);
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 0.25rem;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    min-width: 12rem;
    z-index: 100;
    display: none;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.5rem 1rem;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--gray-700);
}

.dropdown-item:hover {
    background: var(--gray-100);
}

.spinner {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    border: 3px solid rgba(59, 130, 246, 0.2);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.spinner-sm {
    width: 1rem;
    height: 1rem;
    border-width: 2px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.grid {
    display: grid;
    gap: 1rem;
}

.grid-cols-1 {
    grid-template-columns: 1fr;
}

.grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-cols-3 {
    grid-template-columns: repeat(3, 1fr);
}

@media (min-width: 640px) {
    .sm\:grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 768px) {
    .md\:grid-cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .md\:hidden {
        display: none;
    }
    
    .md\:table-cell {
        display: table-cell;
    }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .lg\:table-cell {
        display: table-cell;
    }
}

@media (max-width: 767px) {
    .md\:hidden {
        display: none;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .table-mobile {
        display: block;
    }
    
    .table-desktop {
        display: none;
    }
}

@media (min-width: 768px) {
    .table-mobile {
        display: none;
    }
    
    .table-desktop {
        display: table;
    }
}

.divide-y > :not([hidden]) ~ :not([hidden]) {
    border-top-width: 1px;
}

.divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
    border-color: var(--gray-200);
}

.hover\:bg-gray-50:hover {
    background: var(--gray-50);
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-between {
    justify-content: space-between;
}

.justify-center {
    justify-content: center;
}

.space-x-2 > :not([hidden]) ~ :not([hidden]) {
    margin-left: 0.5rem;
}

.ml-4 {
    margin-left: 1rem;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.p-8 {
    padding: 2rem;
}

.p-12 {
    padding: 3rem;
}

.text-center {
    text-align: center;
}

.text-sm {
    font-size: 0.875rem;
}

.text-lg {
    font-size: 1.125rem;
}

.text-xl {
    font-size: 1.25rem;
}

.text-4xl {
    font-size: 2.25rem;
}

.font-medium {
    font-weight: 500;
}

.uppercase {
    text-transform: uppercase;
}

.tracking-wider {
    letter-spacing: 0.05em;
}

.whitespace-nowrap {
    white-space: nowrap;
}

.overflow-x-auto {
    overflow-x: auto;
}

.w-full {
    width: 100%;
}

.h-10 {
    height: 2.5rem;
}

.w-10 {
    width: 2.5rem;
}

.h-16 {
    height: 4rem;
}

.w-16 {
    width: 4rem;
}

.rounded-full {
    border-radius: 50%;
}

.bg-primary {
    background: var(--primary);
}

.bg-gray-50 {
    background: var(--gray-50);
}

.text-white {
    color: white;
}

.text-gray-500 {
    color: var(--gray-500);
}

.text-gray-900 {
    color: var(--gray-900);
}
</style>

<script>
function userManagement() {
    return {
        // State
        users: [],
        tenants: [],
        roles: [],
        pagination: null,
        loading: false,
        saving: false,
        showModal: false,
        modalMode: 'view', // view, create, edit
        modalTitle: 'User Details',
        selectedUser: {},
        userForm: {
            id: null,
            name: '',
            email: '',
            password: '',
            tenant_id: '',
            role: ''
        },
        filters: {
            search: '',
            role: '',
            status: ''
        },
        toasts: [],

        // Initialize
        init() {
            this.loadUsers();
            this.loadTenants();
            this.loadRoles();
        },

        // Load users with filters
        async loadUsers(url = null) {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    search: this.filters.search,
                    role: this.filters.role,
                    status: this.filters.status
                });
                
                const response = await fetch(url || `/api/v1/superadmin/users?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.users = data.data.data.map(user => ({
                        ...user,
                        initials: this.getUserInitials(user)
                    }));
                    this.pagination = {
                        current_page: data.data.current_page,
                        last_page: data.data.last_page,
                        per_page: data.data.per_page,
                        total: data.data.total,
                        from: data.data.from,
                        to: data.data.to,
                        prev_page_url: data.data.prev_page_url,
                        next_page_url: data.data.next_page_url
                    };
                }
            } catch (error) {
                this.showToast('Error loading users', 'error');
                console.error('Error loading users:', error);
            } finally {
                this.loading = false;
            }
        },

        // Load tenants
        async loadTenants() {
            try {
                const response = await fetch('/api/v1/superadmin/tenants', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.tenants = data.data;
                }
            } catch (error) {
                console.error('Error loading tenants:', error);
            }
        },

        // Load roles
        async loadRoles() {
            try {
                const response = await fetch('/api/v1/superadmin/roles', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.roles = data.data;
                }
            } catch (error) {
                console.error('Error loading roles:', error);
            }
        },

        // Get user initials
        getUserInitials(user) {
            if (user.profile) {
                const firstInitial = user.profile.first_name ? user.profile.first_name.charAt(0).toUpperCase() : '';
                const lastInitial = user.profile.last_name ? user.profile.last_name.charAt(0).toUpperCase() : '';
                return firstInitial + lastInitial;
            }
            return user.email ? user.email.charAt(0).toUpperCase() : 'U';
        },

        // Open create modal
        openCreateModal() {
            this.modalMode = 'create';
            this.modalTitle = 'Create User';
            this.userForm = {
                id: null,
                name: '',
                email: '',
                password: '',
                tenant_id: '',
                role: ''
            };
            this.showModal = true;
        },

        // View user
        viewUser(user) {
            this.modalMode = 'view';
            this.modalTitle = 'User Details';
            this.selectedUser = user;
            this.showModal = true;
        },

        // Edit user
        editUser(user) {
            this.modalMode = 'edit';
            this.modalTitle = 'Edit User';
            this.userForm = {
                id: user.id,
                name: user.profile ? (user.profile.first_name + ' ' + user.profile.last_name) : '',
                email: user.email,
                password: '',
                tenant_id: user.tenant_id || '',
                role: user.roles && user.roles.length > 0 ? user.roles[0].name : ''
            };
            this.showModal = true;
        },

        // Save user (create or update)
        async saveUser() {
            this.saving = true;
            
            try {
                const method = this.modalMode === 'create' ? 'POST' : 'PUT';
                const url = this.modalMode === 'create' 
                    ? '/api/v1/superadmin/users' 
                    : `/api/v1/superadmin/users/${this.userForm.id}`;
                
                // Split name into first and last name
                const nameParts = this.userForm.name.split(' ');
                const firstName = nameParts[0] || '';
                const lastName = nameParts.slice(1).join(' ') || '';
                
                const formData = {
                    email: this.userForm.email,
                    first_name: firstName,
                    last_name: lastName,
                    tenant_id: this.userForm.tenant_id,
                    role: this.userForm.role
                };
                
                // Add password for create
                if (this.modalMode === 'create') {
                    formData.password = this.userForm.password;
                }
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.showToast(this.modalMode === 'create' ? 'User created successfully' : 'User updated successfully', 'success');
                    this.closeModal();
                    this.loadUsers();
                } else {
                    this.showToast('Error saving user', 'error');
                }
            } catch (error) {
                this.showToast('Error saving user', 'error');
                console.error('Error saving user:', error);
            } finally {
                this.saving = false;
            }
        },

        // Activate user
        async activateUser(user) {
            if (!confirm('Are you sure you want to activate this user?')) return;
            
            try {
                const response = await fetch(`/api/v1/superadmin/users/${user.id}/activate`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.showToast('User activated successfully', 'success');
                    this.loadUsers();
                } else {
                    this.showToast('Error activating user', 'error');
                }
            } catch (error) {
                this.showToast('Error activating user', 'error');
                console.error('Error activating user:', error);
            }
        },

        // Deactivate user
        async deactivateUser(user) {
            if (!confirm('Are you sure you want to deactivate this user?')) return;
            
            try {
                const response = await fetch(`/api/v1/superadmin/users/${user.id}/deactivate`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.showToast('User deactivated successfully', 'success');
                    this.loadUsers();
                } else {
                    this.showToast('Error deactivating user', 'error');
                }
            } catch (error) {
                this.showToast('Error deactivating user', 'error');
                console.error('Error deactivating user:', error);
            }
        },

        // Delete user
        async deleteUser(user) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
            
            try {
                const response = await fetch(`/api/v1/superadmin/users/${user.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.showToast('User deleted successfully', 'success');
                    this.loadUsers();
                } else {
                    this.showToast('Error deleting user', 'error');
                }
            } catch (error) {
                this.showToast('Error deleting user', 'error');
                console.error('Error deleting user:', error);
            }
        },

        // Close modal
        closeModal() {
            this.showModal = false;
            this.selectedUser = {};
        },

        // Show toast notification
        showToast(message, type = 'success') {
            const toast = {
                id: Date.now(),
                message: message,
                type: type,
                icon: type === 'success' ? 'ti ti-check' : (type === 'error' ? 'ti ti-x' : (type === 'warning' ? 'ti ti-alert-circle' : 'ti ti-info-circle')),
                visible: true
            };
            
            this.toasts.push(toast);
            
            // Auto remove toast after 5 seconds
            setTimeout(() => {
                this.removeToast(toast.id);
            }, 5000);
        },

        // Remove toast notification
        removeToast(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        },

        // Reset filters
        resetFilters() {
            this.filters = {
                search: '',
                role: '',
                status: ''
            };
            this.loadUsers();
        }
    }
}
</script>