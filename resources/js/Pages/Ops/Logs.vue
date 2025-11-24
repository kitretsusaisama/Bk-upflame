<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Ops</span> <span class="breadcrumb-sep">/</span> <span>Logs</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Audit Logs</h1>
      <div class="page-actions">
        <button class="btn btn-sm btn-outline-primary">Export Logs</button>
        <button class="btn btn-sm btn-outline-secondary">Clear Filters</button>
      </div>
    </div>
    
    <Card>
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div>
            <label for="dateFilter" class="form-label">Date Range</label>
            <input 
              type="date" 
              class="form-control" 
              id="dateFilter"
              v-model="filters.date"
            >
          </div>
          
          <div>
            <label for="userFilter" class="form-label">User</label>
            <select 
              class="form-control" 
              id="userFilter"
              v-model="filters.user"
            >
              <option value="">All Users</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label for="actionFilter" class="form-label">Action Type</label>
            <select 
              class="form-control" 
              id="actionFilter"
              v-model="filters.action"
            >
              <option value="">All Actions</option>
              <option value="create">Create</option>
              <option value="update">Update</option>
              <option value="delete">Delete</option>
              <option value="approve">Approve</option>
              <option value="reject">Reject</option>
            </select>
          </div>
          
          <div>
            <label for="searchFilter" class="form-label">Search</label>
            <input 
              type="text" 
              class="form-control" 
              id="searchFilter" 
              placeholder="Search logs..."
              v-model="filters.search"
            >
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <Table 
            :headers="['Timestamp', 'User', 'Action', 'Resource', 'Details', 'IP Address']"
            :loading="loading"
          >
            <tr v-for="log in logs" :key="log.id">
              <td>{{ formatDate(log.timestamp) }}</td>
              <td>{{ log.user }}</td>
              <td>
                <Badge :type="getActionBadgeType(log.action)">
                  {{ log.action }}
                </Badge>
              </td>
              <td>{{ log.resource }}</td>
              <td>{{ log.details }}</td>
              <td>{{ log.ipAddress }}</td>
            </tr>
            
            <tr v-if="logs.length === 0 && !loading">
              <td colspan="6" class="text-center py-4">No logs found</td>
            </tr>
          </Table>
        </div>
        
        <div class="flex justify-center mt-6" v-if="logs.length > 0">
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
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { useQuery } from '@tanstack/vue-query'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const logs = ref([])
const users = ref([])
const loading = ref(false)
const currentPage = ref(1)
const totalPages = ref(1)

const filters = reactive({
  date: '',
  user: '',
  action: '',
  search: ''
})

// Methods
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString()
}

const getActionBadgeType = (action) => {
  const badgeTypes = {
    'create': 'info',
    'update': 'warning',
    'delete': 'danger',
    'approve': 'success',
    'reject': 'danger'
  }
  return badgeTypes[action.toLowerCase()] || 'secondary'
}

const changePage = (page) => {
  currentPage.value = page
  // In a real implementation, this would fetch the new page of data
}

// Mock data for demonstration
onMounted(() => {
  // Mock logs data
  logs.value = [
    {
      id: 1,
      timestamp: '2023-12-15T14:30:22',
      user: 'John Admin',
      action: 'approve',
      resource: 'Workflow WF-2023-001',
      details: 'Provider onboarding approved',
      ipAddress: '192.168.1.100'
    },
    {
      id: 2,
      timestamp: '2023-12-15T14:25:18',
      user: 'Jane Ops',
      action: 'update',
      resource: 'Booking BK-2023-045',
      details: 'Rescheduled appointment',
      ipAddress: '192.168.1.101'
    },
    {
      id: 3,
      timestamp: '2023-12-15T14:15:44',
      user: 'System',
      action: 'create',
      resource: 'Workflow WF-2023-042',
      details: 'New provider onboarding started',
      ipAddress: '192.168.1.1'
    }
  ]
  
  // Mock users data
  users.value = [
    { id: 1, name: 'John Admin' },
    { id: 2, name: 'Jane Ops' }
  ]
  
  totalPages.value = 3
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

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #64748b;
}

.form-control {
  display: block;
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-outline-primary {
  border: 1px solid #3b82f6;
  color: #3b82f6;
  background-color: transparent;
}

.btn-outline-primary:hover {
  background-color: #3b82f6;
  color: white;
}

.btn-outline-secondary {
  border: 1px solid #94a3b8;
  color: #94a3b8;
  background-color: transparent;
}

.btn-outline-secondary:hover {
  background-color: #94a3b8;
  color: white;
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
</style>