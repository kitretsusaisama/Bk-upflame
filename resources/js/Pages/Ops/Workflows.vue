<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Ops</span> <span class="breadcrumb-sep">/</span> <span>Workflows</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Workflow Management</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showCreateWorkflowModal = true">
          <span>➕</span> Create Workflow
        </button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Active Workflows</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Types</option>
            <option>Booking</option>
            <option>User Management</option>
            <option>Service</option>
            <option>Payment</option>
          </select>
          <input type="text" placeholder="Search workflows..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['Workflow', 'Type', 'Status', 'Created By', 'Created At', 'Actions']">
        <tr v-for="i in 10" :key="i">
          <td>
            <div class="workflow-name">Workflow {{ i }}</div>
            <div class="workflow-description">Description for workflow {{ i }}</div>
          </td>
          <td>
            <Badge v-if="i % 4 === 0" type="primary">Booking</Badge>
            <Badge v-else-if="i % 4 === 1" type="info">User Management</Badge>
            <Badge v-else-if="i % 4 === 2" type="warning">Service</Badge>
            <Badge v-else type="success">Payment</Badge>
          </td>
          <td>
            <Badge v-if="i % 3 === 0" type="success">Active</Badge>
            <Badge v-else-if="i % 3 === 1" type="warning">Pending</Badge>
            <Badge v-else type="error">Inactive</Badge>
          </td>
          <td>
            <div class="table-user">
              <div class="user-avatar">{{ getRandomInitial() }}</div>
              <div class="user-details">
                <div class="user-name">User {{ i }}</div>
              </div>
            </div>
          </td>
          <td>{{ formatDate(subDays(new Date(), i)) }}</td>
          <td>
            <div class="table-actions">
              <button class="btn-icon" title="View">👁️</button>
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
    
    <!-- Create Workflow Modal -->
    <div v-if="showCreateWorkflowModal" class="modal-overlay" @click="showCreateWorkflowModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Create New Workflow</h3>
          <button class="modal-close" @click="showCreateWorkflowModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createWorkflow">
            <div class="form-group">
              <label class="form-label">Workflow Name</label>
              <input type="text" class="form-input" v-model="workflowForm.name" placeholder="Enter workflow name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea class="form-textarea" v-model="workflowForm.description" rows="3" placeholder="Enter workflow description"></textarea>
            </div>
            
            <div class="form-group">
              <label class="form-label">Type</label>
              <select class="form-select" v-model="workflowForm.type">
                <option value="">Select a type</option>
                <option value="booking">Booking</option>
                <option value="user">User Management</option>
                <option value="service">Service</option>
                <option value="payment">Payment</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Trigger</label>
              <select class="form-select" v-model="workflowForm.trigger">
                <option value="">Select a trigger</option>
                <option value="manual">Manual</option>
                <option value="scheduled">Scheduled</option>
                <option value="event">Event-based</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Priority</label>
              <div class="priority-options">
                <label class="priority-option">
                  <input type="radio" v-model="workflowForm.priority" value="low">
                  <span class="priority-label priority-low">Low</span>
                </label>
                <label class="priority-option">
                  <input type="radio" v-model="workflowForm.priority" value="medium">
                  <span class="priority-label priority-medium">Medium</span>
                </label>
                <label class="priority-option">
                  <input type="radio" v-model="workflowForm.priority" value="high">
                  <span class="priority-label priority-high">High</span>
                </label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showCreateWorkflowModal = false">Cancel</button>
          <button class="btn btn-primary" @click="createWorkflow">Create Workflow</button>
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
const showCreateWorkflowModal = ref(false)

// Form data
const workflowForm = reactive({
  name: '',
  description: '',
  type: '',
  trigger: '',
  priority: 'medium'
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
function createWorkflow() {
  console.log('Creating workflow:', workflowForm)
  // In a real app, you would send this data to your backend
  alert('Workflow created successfully!')
  
  // Reset form and close modal
  workflowForm.name = ''
  workflowForm.description = ''
  workflowForm.type = ''
  workflowForm.trigger = ''
  workflowForm.priority = 'medium'
  showCreateWorkflowModal.value = false
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

.workflow-name {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.workflow-description {
  font-size: 0.875rem;
  color: #64748b;
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

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.priority-options {
  display: flex;
  gap: 1rem;
}

.priority-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.priority-label {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
}

.priority-low {
  background-color: #dcfce7;
  color: #166534;
}

.priority-medium {
  background-color: #fef3c7;
  color: #92400e;
}

.priority-high {
  background-color: #fee2e2;
  color: #991b1b;
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

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .card-actions {
    flex-direction: column;
  }
  
  .priority-options {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>