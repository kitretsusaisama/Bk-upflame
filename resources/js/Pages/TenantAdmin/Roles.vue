<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Roles</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Role Management</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showCreateRoleModal = true">
          <span>➕</span> Add Role
        </button>
      </div>
    </div>
    
    <div class="grid grid-2">
      <Card title="Roles">
        <Table :headers="['Role', 'Permissions', 'Users', 'Actions']">
          <tr v-for="i in 6" :key="i">
            <td>
              <div class="role-name">Role {{ i }}</div>
            </td>
            <td>{{ Math.floor(Math.random() * 10) + 5 }} permissions</td>
            <td>{{ Math.floor(Math.random() * 50) + 10 }} users</td>
            <td>
              <div class="table-actions">
                <button class="btn-icon" title="Edit">✏️</button>
                <button class="btn-icon" title="Delete">🗑️</button>
              </div>
            </td>
          </tr>
        </Table>
      </Card>
      
      <Card title="Permissions">
        <div class="permissions-list">
          <div v-for="i in 15" :key="i" class="permission-item">
            <label class="permission-label">
              <input type="checkbox" class="permission-checkbox">
              <span class="permission-name">permission.{{ i }}</span>
            </label>
          </div>
        </div>
      </Card>
    </div>
    
    <!-- Create Role Modal -->
    <div v-if="showCreateRoleModal" class="modal-overlay" @click="showCreateRoleModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add New Role</h3>
          <button class="modal-close" @click="showCreateRoleModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createRole">
            <div class="form-group">
              <label class="form-label">Role Name</label>
              <input type="text" class="form-input" v-model="roleForm.name" placeholder="Enter role name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea class="form-textarea" v-model="roleForm.description" rows="3" placeholder="Enter role description"></textarea>
            </div>
            
            <div class="form-group">
              <label class="form-label">Permissions</label>
              <div class="permissions-grid">
                <div v-for="i in 12" :key="i" class="permission-checkbox-item">
                  <label class="permission-label">
                    <input type="checkbox" class="permission-checkbox">
                    <span class="permission-name">permission.{{ i }}</span>
                  </label>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showCreateRoleModal = false">Cancel</button>
          <button class="btn btn-primary" @click="createRole">Create Role</button>
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

// Modal state
const showCreateRoleModal = ref(false)

// Form data
const roleForm = reactive({
  name: '',
  description: ''
})

// Form submission handler
function createRole() {
  console.log('Creating role:', roleForm)
  // In a real app, you would send this data to your backend
  alert('Role created successfully!')
  
  // Reset form and close modal
  roleForm.name = ''
  roleForm.description = ''
  showCreateRoleModal.value = false
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

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.role-name {
  font-weight: 600;
  color: #1e293b;
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

.permissions-list {
  max-height: 400px;
  overflow-y: auto;
}

.permission-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
}

.permission-item:last-child {
  border-bottom: none;
}

.permission-label {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
}

.permission-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 0.25rem;
  border: 1px solid #cbd5e1;
  cursor: pointer;
}

.permission-name {
  font-size: 0.875rem;
  color: #1e293b;
}

.permissions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.5rem;
  max-height: 200px;
  overflow-y: auto;
  padding: 0.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
}

.permission-checkbox-item {
  padding: 0.25rem 0;
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
.form-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
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
  max-width: 600px;
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

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .permissions-grid {
    grid-template-columns: 1fr;
  }
}
</style>