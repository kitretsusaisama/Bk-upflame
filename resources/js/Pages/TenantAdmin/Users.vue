<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Users</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">User Management</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showCreateUserModal = true">
          <span>➕</span> Add User
        </button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Users</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Roles</option>
            <option>Customer</option>
            <option>Provider</option>
            <option>Operations</option>
          </select>
          <input type="text" placeholder="Search users..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['User', 'Email', 'Role', 'Status', 'Last Login', 'Actions']">
        <tr v-for="i in 10" :key="i">
          <td>
            <div class="table-user">
              <div class="user-avatar">{{ getRandomInitial() }}</div>
              <div class="user-details">
                <div class="user-name">User {{ i }}</div>
              </div>
            </div>
          </td>
          <td>user{{ i }}@example.com</td>
          <td>
            <Badge v-if="i % 3 === 0" type="primary">Customer</Badge>
            <Badge v-else-if="i % 3 === 1" type="info">Provider</Badge>
            <Badge v-else type="warning">Operations</Badge>
          </td>
          <td>
            <Badge v-if="i % 2 === 0" type="success">Active</Badge>
            <Badge v-else type="error">Inactive</Badge>
          </td>
          <td>{{ formatDate(subDays(new Date(), i)) }}</td>
          <td>
            <div class="table-actions">
              <button class="btn-icon" title="Edit">✏️</button>
              <button class="btn-icon" title="Delete">🗑️</button>
            </div>
          </td>
        </tr>
      </Table>
      
      <template #footer>
        <div class="pagination">
          <button class="btn btn-outline">Previous</button>
          <span class="pagination-info">Page 1 of 5</span>
          <button class="btn btn-outline">Next</button>
        </div>
      </template>
    </Card>
    
    <!-- Create User Modal -->
    <div v-if="showCreateUserModal" class="modal-overlay" @click="showCreateUserModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add New User</h3>
          <button class="modal-close" @click="showCreateUserModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createUser">
            <div class="form-group">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-input" v-model="userForm.name" placeholder="Enter full name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-input" v-model="userForm.email" placeholder="Enter email">
            </div>
            
            <div class="form-group">
              <label class="form-label">Role</label>
              <select class="form-select" v-model="userForm.role">
                <option value="">Select a role</option>
                <option value="customer">Customer</option>
                <option value="provider">Provider</option>
                <option value="operations">Operations</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Password</label>
              <input type="password" class="form-input" v-model="userForm.password" placeholder="Enter password">
            </div>
            
            <div class="form-group">
              <label class="form-label">Confirm Password</label>
              <input type="password" class="form-input" v-model="userForm.passwordConfirm" placeholder="Confirm password">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showCreateUserModal = false">Cancel</button>
          <button class="btn btn-primary" @click="createUser">Create User</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Modal state
const showCreateUserModal = ref(false)

// Form data
const userForm = reactive({
  name: '',
  email: '',
  role: '',
  password: '',
  passwordConfirm: ''
})

// Helper functions
function getRandomInitial() {
  const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  return letters.charAt(Math.floor(Math.random() * letters.length))
}

function formatDate(date) {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric' 
  })
}

function subDays(date, days) {
  const result = new Date(date)
  result.setDate(result.getDate() - days)
  return result
}

// Form submission handler
function createUser() {
  if (userForm.password !== userForm.passwordConfirm) {
    alert('Passwords do not match!')
    return
  }
  
  console.log('Creating user:', userForm)
  // In a real app, you would send this data to your backend
  alert('User created successfully!')
  
  // Reset form and close modal
  userForm.name = ''
  userForm.email = ''
  userForm.role = ''
  userForm.password = ''
  userForm.passwordConfirm = ''
  showCreateUserModal.value = false
}
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
  margin: 0;
}

.page-actions {
  display: flex;
  gap: 0.5rem;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

.form-select {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.form-input {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.table-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 9999px;
  background-color: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.75rem;
}

.user-details {
  min-width: 0;
}

.user-name {
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.table-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  background-color: #f1f5f9;
  color: #64748b;
  text-decoration: none;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
}

.btn-icon:hover {
  background-color: #e2e8f0;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0;
}

.pagination-info {
  color: #64748b;
  font-size: 0.875rem;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #94a3b8;
  padding: 0;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #64748b;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.form-input,
.form-select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}
</style>