<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Users</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Manage Users</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="openCreateModal">
          Create New User
        </button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input 
            type="text" 
            class="form-control" 
            id="searchInput"
            placeholder="Search users..."
            v-model="searchTerm"
          >
          <select class="form-control" id="roleFilter" v-model="roleFilter">
            <option value="">All Roles</option>
            <option value="Super Admin">Super Admin</option>
            <option value="Tenant Admin">Tenant Admin</option>
            <option value="Provider">Provider</option>
            <option value="Customer">Customer</option>
            <option value="Operations">Operations</option>
          </select>
        </div>
      </template>
      
      <template #body>
        <!-- Loading indicator -->
        <div v-if="loading" class="loading-container">
          <LoadingSpinner :size="'md'" :message="'Loading users...'" />
        </div>
        
        <!-- Users table -->
        <div v-else class="table-responsive">
          <Table :headers="['Name', 'Email', 'Tenant', 'Role', 'Status', 'Actions']">
            <tr v-for="user in filteredUsers" :key="user.id" :data-user-id="user.id">
              <td>{{ user.profile ? user.profile.fullName : 'N/A' }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.tenant ? user.tenant.name : 'N/A' }}</td>
              <td>
                <div v-if="user.roles.length > 0">
                  <Badge 
                    v-for="role in user.roles" 
                    :key="role.id" 
                    type="info"
                    class="mr-1"
                  >
                    {{ role.name }}
                  </Badge>
                </div>
                <Badge v-else type="secondary">No Role</Badge>
              </td>
              <td>
                <Badge :type="getStatusBadgeType(user.status)">
                  {{ capitalize(user.status) }}
                </Badge>
              </td>
              <td>
                <button 
                  class="btn btn-sm btn-info view-user mr-1" 
                  :data-user-id="user.id"
                  @click="viewUser(user)"
                >
                  View
                </button>
                <button 
                  class="btn btn-sm btn-warning edit-user mr-1" 
                  :data-user-id="user.id"
                  @click="openEditModal(user)"
                >
                  Edit
                </button>
                <button 
                  v-if="user.status === 'active'"
                  class="btn btn-sm btn-danger deactivate-user" 
                  :data-user-id="user.id"
                  @click="deactivateUser(user)"
                >
                  Deactivate
                </button>
                <button 
                  v-else
                  class="btn btn-sm btn-success activate-user" 
                  :data-user-id="user.id"
                  @click="activateUser(user)"
                >
                  Activate
                </button>
              </td>
            </tr>
            
            <!-- Empty state -->
            <tr v-if="filteredUsers.length === 0 && !loading">
              <td colspan="6" class="text-center py-4">
                <div class="empty-state">
                  <p>No users found</p>
                  <button class="btn btn-primary" @click="openCreateModal">
                    Create New User
                  </button>
                </div>
              </td>
            </tr>
          </Table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container" v-if="filteredUsers.length > 0 && !loading">
          <nav class="pagination">
            <button 
              class="pagination-btn" 
              :disabled="currentPage === 1"
              @click="changePage(currentPage - 1)"
            >
              Previous
            </button>
            
            <button 
              v-for="page in totalPages" 
              :key="page"
              class="pagination-btn"
              :class="{ 'active': page === currentPage }"
              @click="changePage(page)"
            >
              {{ page }}
            </button>
            
            <button 
              class="pagination-btn" 
              :disabled="currentPage === totalPages"
              @click="changePage(currentPage + 1)"
            >
              Next
            </button>
          </nav>
        </div>
      </template>
    </Card>
    
    <!-- Create User Modal -->
    <div class="modal" :class="{ 'show': showCreateModal }" v-if="showCreateModal">
      <div class="modal-overlay" @click="closeCreateModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create New User</h5>
          <button type="button" class="close" @click="closeCreateModal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- Loading indicator for form submission -->
          <div v-if="formLoading" class="form-loading">
            <LoadingSpinner :size="'sm'" :message="'Creating user...'" />
          </div>
          
          <form v-else id="createUserForm" @submit.prevent="createUser">
            <div class="form-group">
              <label for="userName">Full Name</label>
              <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.name }"
                id="userName" 
                v-model="newUser.name"
                @blur="touchField('name')"
                required
              >
              <div v-if="errors.name" class="invalid-feedback">
                {{ errors.name }}
              </div>
            </div>
            <div class="form-group">
              <label for="userEmail">Email Address</label>
              <input 
                type="email" 
                class="form-control" 
                :class="{ 'is-invalid': errors.email }"
                id="userEmail" 
                v-model="newUser.email"
                @blur="touchField('email')"
                required
              >
              <div v-if="errors.email" class="invalid-feedback">
                {{ errors.email }}
              </div>
            </div>
            <div class="form-group">
              <label for="userPassword">Password</label>
              <input 
                type="password" 
                class="form-control" 
                :class="{ 'is-invalid': errors.password }"
                id="userPassword" 
                v-model="newUser.password"
                @blur="touchField('password')"
                required
              >
              <div v-if="errors.password" class="invalid-feedback">
                {{ errors.password }}
              </div>
            </div>
            <div class="form-group">
              <label for="userTenant">Tenant</label>
              <select class="form-control" id="userTenant" v-model="newUser.tenantId">
                <option value="">Select Tenant</option>
                <option 
                  v-for="tenant in tenants" 
                  :key="tenant.id" 
                  :value="tenant.id"
                >
                  {{ tenant.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="userRole">Role</label>
              <select class="form-control" id="userRole" v-model="newUser.role">
                <option value="">Select Role</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Tenant Admin">Tenant Admin</option>
                <option value="Provider">Provider</option>
                <option value="Customer">Customer</option>
                <option value="Operations">Operations</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeCreateModal" :disabled="formLoading">
            Cancel
          </button>
          <button type="button" class="btn btn-primary" @click="createUser" :disabled="formLoading">
            Create User
          </button>
        </div>
      </div>
    </div>
    
    <!-- Edit User Modal -->
    <div class="modal" :class="{ 'show': showEditModal }" v-if="showEditModal">
      <div class="modal-overlay" @click="closeEditModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="close" @click="closeEditModal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- Loading indicator for form submission -->
          <div v-if="formLoading" class="form-loading">
            <LoadingSpinner :size="'sm'" :message="'Updating user...'" />
          </div>
          
          <form v-else id="editUserForm" @submit.prevent="updateUser">
            <div class="form-group">
              <label for="editUserName">Full Name</label>
              <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.name }"
                id="editUserName" 
                v-model="editingUser.name"
                @blur="touchField('name')"
                required
              >
              <div v-if="errors.name" class="invalid-feedback">
                {{ errors.name }}
              </div>
            </div>
            <div class="form-group">
              <label for="editUserEmail">Email Address</label>
              <input 
                type="email" 
                class="form-control" 
                :class="{ 'is-invalid': errors.email }"
                id="editUserEmail" 
                v-model="editingUser.email"
                @blur="touchField('email')"
                required
              >
              <div v-if="errors.email" class="invalid-feedback">
                {{ errors.email }}
              </div>
            </div>
            <div class="form-group">
              <label for="editUserTenant">Tenant</label>
              <select class="form-control" id="editUserTenant" v-model="editingUser.tenantId">
                <option value="">Select Tenant</option>
                <option 
                  v-for="tenant in tenants" 
                  :key="tenant.id" 
                  :value="tenant.id"
                >
                  {{ tenant.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="editUserRole">Role</label>
              <select class="form-control" id="editUserRole" v-model="editingUser.role">
                <option value="">Select Role</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Tenant Admin">Tenant Admin</option>
                <option value="Provider">Provider</option>
                <option value="Customer">Customer</option>
                <option value="Operations">Operations</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeEditModal" :disabled="formLoading">
            Cancel
          </button>
          <button type="button" class="btn btn-primary" @click="updateUser" :disabled="formLoading">
            Update User
          </button>
        </div>
      </div>
    </div>
    
    <!-- View User Modal -->
    <div class="modal" :class="{ 'show': showViewModal }" v-if="showViewModal">
      <div class="modal-overlay" @click="closeViewModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User Details</h5>
          <button type="button" class="close" @click="closeViewModal">&times;</button>
        </div>
        <div class="modal-body">
          <div id="userDetails" v-if="viewingUser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p><strong>Name:</strong> {{ viewingUser.profile ? viewingUser.profile.fullName : 'N/A' }}</p>
                <p><strong>Email:</strong> {{ viewingUser.email }}</p>
                <p><strong>Status:</strong> {{ viewingUser.status }}</p>
              </div>
              <div>
                <p><strong>Tenant:</strong> {{ viewingUser.tenant ? viewingUser.tenant.name : 'N/A' }}</p>
                <p><strong>Role:</strong> {{ viewingUser.roles.length > 0 ? viewingUser.roles[0].name : 'N/A' }}</p>
                <p><strong>Created:</strong> {{ formatDate(viewingUser.createdAt) }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeViewModal">Close</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'
import LoadingSpinner from '@/Components/UI/LoadingSpinner.vue'
import { useValidation } from '@/composables/useValidation'
import { handleFormError, showSuccessNotification } from '@/utils/errorHandler'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const users = ref([])
const tenants = ref([])
const searchTerm = ref('')
const roleFilter = ref('')
const currentPage = ref(1)
const totalPages = ref(1)
const loading = ref(false)
const formLoading = ref(false)

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)

// Form data
const newUser = ref({
  name: '',
  email: '',
  password: '',
  tenantId: '',
  role: ''
})

const editingUser = ref({
  id: null,
  name: '',
  email: '',
  tenantId: '',
  role: ''
})

const viewingUser = ref(null)

// Validation
const { validateUserForm, errors, touchField, clearErrors, resetValidation } = useValidation()

// Computed
const filteredUsers = computed(() => {
  let result = users.value
  
  // Apply search filter
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase()
    result = result.filter(user => 
      (user.profile && user.profile.fullName.toLowerCase().includes(term)) || 
      user.email.toLowerCase().includes(term)
    )
  }
  
  // Apply role filter
  if (roleFilter.value) {
    result = result.filter(user => 
      user.roles.some(role => role.name === roleFilter.value)
    )
  }
  
  return result
})

// Methods
const getStatusBadgeType = (status) => {
  const badgeTypes = {
    'active': 'success',
    'inactive': 'warning',
    'pending': 'info'
  }
  return badgeTypes[status] || 'secondary'
}

const capitalize = (str) => {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString()
}

const openCreateModal = () => {
  showCreateModal.value = true
}

const closeCreateModal = () => {
  showCreateModal.value = false
  newUser.value = {
    name: '',
    email: '',
    password: '',
    tenantId: '',
    role: ''
  }
  resetValidation()
}

const openEditModal = (user) => {
  editingUser.value = {
    id: user.id,
    name: user.profile ? user.profile.fullName : '',
    email: user.email,
    tenantId: user.tenant ? user.tenant.id : '',
    role: user.roles.length > 0 ? user.roles[0].name : ''
  }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingUser.value = {
    id: null,
    name: '',
    email: '',
    tenantId: '',
    role: ''
  }
  resetValidation()
}

const openViewModal = (user) => {
  viewingUser.value = user
  showViewModal.value = true
}

const closeViewModal = () => {
  showViewModal.value = false
  viewingUser.value = null
}

const viewUser = (user) => {
  openViewModal(user)
}

const createUser = async () => {
  // Validate form
  if (!validateUserForm(newUser.value)) {
    return
  }
  
  // Clear previous errors
  clearErrors()
  
  // Show loading state
  formLoading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // In a real implementation, this would make an API call
    const user = {
      id: users.value.length + 1,
      email: newUser.value.email,
      status: 'active',
      profile: {
        fullName: newUser.value.name
      },
      tenant: tenants.value.find(t => t.id === parseInt(newUser.value.tenantId)) || null,
      roles: newUser.value.role ? [{ id: 1, name: newUser.value.role }] : [],
      createdAt: new Date().toISOString()
    }
    
    users.value.push(user)
    showSuccessNotification('User created successfully')
    closeCreateModal()
  } catch (error) {
    handleFormError(error)
  } finally {
    formLoading.value = false
  }
}

const updateUser = async () => {
  // Validate form
  const updateData = {
    name: editingUser.value.name,
    email: editingUser.value.email
  }
  
  // For update, we don't require password
  if (!validateUserForm(updateData)) {
    return
  }
  
  // Clear previous errors
  clearErrors()
  
  // Show loading state
  formLoading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // In a real implementation, this would make an API call
    const userIndex = users.value.findIndex(u => u.id === editingUser.value.id)
    if (userIndex !== -1) {
      const user = users.value[userIndex]
      user.email = editingUser.value.email
      user.profile = {
        fullName: editingUser.value.name
      }
      user.tenant = tenants.value.find(t => t.id === parseInt(editingUser.value.tenantId)) || null
      user.roles = editingUser.value.role ? [{ id: 1, name: editingUser.value.role }] : []
    }
    
    showSuccessNotification('User updated successfully')
    closeEditModal()
  } catch (error) {
    handleFormError(error)
  } finally {
    formLoading.value = false
  }
}

const activateUser = async (user) => {
  // Show loading state
  loading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // In a real implementation, this would make an API call
    user.status = 'active'
    showSuccessNotification('User activated successfully')
  } catch (error) {
    handleFormError(error)
  } finally {
    loading.value = false
  }
}

const deactivateUser = async (user) => {
  // Show loading state
  loading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // In a real implementation, this would make an API call
    user.status = 'inactive'
    showSuccessNotification('User deactivated successfully')
  } catch (error) {
    handleFormError(error)
  } finally {
    loading.value = false
  }
}

const changePage = (page) => {
  currentPage.value = page
}

// Mock data for demonstration
onMounted(async () => {
  // Show loading state while fetching data
  loading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Mock tenants data
    tenants.value = [
      { id: 1, name: 'Acme Corp' },
      { id: 2, name: 'Globex Inc' },
      { id: 3, name: 'Wayne Enterprises' }
    ]
    
    // Mock users data
    users.value = [
      {
        id: 1,
        email: 'john.admin@example.com',
        status: 'active',
        profile: {
          fullName: 'John Admin'
        },
        tenant: tenants.value[0],
        roles: [{ id: 1, name: 'Super Admin' }],
        createdAt: '2023-10-15T10:30:00Z'
      },
      {
        id: 2,
        email: 'jane.ops@example.com',
        status: 'active',
        profile: {
          fullName: 'Jane Operations'
        },
        tenant: tenants.value[1],
        roles: [{ id: 2, name: 'Operations' }],
        createdAt: '2023-11-01T14:22:00Z'
      },
      {
        id: 3,
        email: 'bob.provider@example.com',
        status: 'inactive',
        profile: {
          fullName: 'Bob Provider'
        },
        tenant: tenants.value[2],
        roles: [{ id: 3, name: 'Provider' }],
        createdAt: '2023-09-22T09:15:00Z'
      }
    ]
    
    totalPages.value = 3
  } catch (error) {
    handleFormError(error)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
}

.page-actions {
  display: flex;
  gap: 0.5rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-secondary {
  background-color: #94a3b8;
  color: white;
}

.btn-secondary:hover {
  background-color: #64748b;
}

.btn-info {
  background-color: #0ea5e9;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-info:hover {
  background-color: #0284c7;
}

.btn-warning {
  background-color: #f59e0b;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-warning:hover {
  background-color: #d97706;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-danger:hover {
  background-color: #dc2626;
}

.btn-success {
  background-color: #10b981;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-success:hover {
  background-color: #059669;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.mr-1 {
  margin-right: 0.25rem;
}

.form-control {
  display: block;
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
}

.form-control.is-invalid {
  border-color: #ef4444;
}

.invalid-feedback {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #ef4444;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #334155;
}

.grid {
  display: grid;
  gap: 1rem;
}

.table-responsive {
  overflow-x: auto;
}

.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 1.5rem;
}

.pagination {
  display: flex;
  gap: 0.25rem;
}

.pagination-btn {
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e1;
  background-color: white;
  color: #64748b;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  background-color: #f1f5f9;
  color: #1e293b;
}

.pagination-btn.active {
  background-color: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1000;
  display: none;
}

.modal.show {
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
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  position: relative;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  z-index: 1001;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin: 0;
}

.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
}

.modal-body {
  padding: 1.25rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1rem 1.25rem;
  border-top: 1px solid #e2e8f0;
}

/* Loading states */
.loading-container {
  display: flex;
  justify-content: center;
  padding: 2rem;
}

.form-loading {
  display: flex;
  justify-content: center;
  padding: 1rem;
}

.empty-state {
  padding: 2rem;
  text-align: center;
  color: #94a3b8;
}

.empty-state p {
  margin-bottom: 1rem;
}

.text-center {
  text-align: center;
}

.py-4 {
  padding-top: 1rem;
  padding-bottom: 1rem;
}
</style>