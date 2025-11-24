<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Logs</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">System Logs</h1>
      <div class="page-actions">
        <button class="btn btn-sm btn-outline-primary">Export Logs</button>
        <button class="btn btn-sm btn-outline-secondary">Clear Filters</button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <label for="levelFilter" class="form-label">Log Level</label>
            <select 
              class="form-control" 
              id="levelFilter"
              v-model="filters.level"
            >
              <option value="">All Levels</option>
              <option value="info">Info</option>
              <option value="warning">Warning</option>
              <option value="error">Error</option>
              <option value="critical">Critical</option>
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
      </template>
      
      <template #body>
        <div class="table-responsive">
          <Table 
            :headers="['Timestamp', 'Level', 'User', 'Message', 'Context', 'Actions']"
            :loading="loading"
          >
            <tr v-for="log in filteredLogs" :key="log.id">
              <td>{{ formatDate(log.timestamp) }}</td>
              <td>
                <Badge :type="getLevelBadgeType(log.level)">
                  {{ log.level }}
                </Badge>
              </td>
              <td>{{ log.user || 'System' }}</td>
              <td>{{ log.message }}</td>
              <td>
                <button 
                  v-if="log.context" 
                  class="btn btn-sm btn-outline-info"
                  @click="viewContext(log.context)"
                >
                  View
                </button>
                <span v-else>-</span>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary">Details</button>
              </td>
            </tr>
            
            <tr v-if="filteredLogs.length === 0 && !loading">
              <td colspan="6" class="text-center py-4">No logs found</td>
            </tr>
          </Table>
        </div>
        
        <div class="flex justify-center mt-6" v-if="filteredLogs.length > 0">
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
    
    <!-- Context Modal -->
    <div class="modal" :class="{ 'show': showContextModal }" v-if="showContextModal">
      <div class="modal-overlay" @click="closeContextModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Log Context</h5>
          <button type="button" class="close" @click="closeContextModal">&times;</button>
        </div>
        <div class="modal-body">
          <pre class="log-context">{{ currentContext }}</pre>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeContextModal">Close</button>
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
const showContextModal = ref(false)
const currentContext = ref('')

const filters = ref({
  date: '',
  user: '',
  level: '',
  search: ''
})

// Computed
const filteredLogs = computed(() => {
  let result = logs.value
  
  // Apply filters
  if (filters.value.date) {
    result = result.filter(log => 
      log.timestamp.startsWith(filters.value.date)
    )
  }
  
  if (filters.value.user) {
    result = result.filter(log => 
      log.user && log.user.id === parseInt(filters.value.user)
    )
  }
  
  if (filters.value.level) {
    result = result.filter(log => 
      log.level === filters.value.level
    )
  }
  
  if (filters.value.search) {
    const term = filters.value.search.toLowerCase()
    result = result.filter(log => 
      log.message.toLowerCase().includes(term) ||
      (log.user && log.user.name.toLowerCase().includes(term))
    )
  }
  
  return result
})

// Methods
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString()
}

const getLevelBadgeType = (level) => {
  const badgeTypes = {
    'info': 'info',
    'warning': 'warning',
    'error': 'danger',
    'critical': 'danger'
  }
  return badgeTypes[level] || 'secondary'
}

const viewContext = (context) => {
  currentContext.value = JSON.stringify(context, null, 2)
  showContextModal.value = true
}

const closeContextModal = () => {
  showContextModal.value = false
  currentContext.value = ''
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
      timestamp: '2023-12-15T14:30:22Z',
      level: 'info',
      user: { id: 1, name: 'John Admin' },
      message: 'User login successful',
      context: { ip: '192.168.1.100', userAgent: 'Mozilla/5.0...' }
    },
    {
      id: 2,
      timestamp: '2023-12-15T14:25:18Z',
      level: 'warning',
      user: { id: 2, name: 'Jane Ops' },
      message: 'High memory usage detected',
      context: { usage: '85%', threshold: '80%' }
    },
    {
      id: 3,
      timestamp: '2023-12-15T14:15:44Z',
      level: 'error',
      user: null,
      message: 'Database connection failed',
      context: { host: 'db.example.com', error: 'Timeout' }
    },
    {
      id: 4,
      timestamp: '2023-12-15T14:10:32Z',
      level: 'info',
      user: { id: 3, name: 'Bob Provider' },
      message: 'New booking created',
      context: { bookingId: 'BK-2023-045', service: 'Consultation' }
    },
    {
      id: 5,
      timestamp: '2023-12-15T14:05:11Z',
      level: 'critical',
      user: null,
      message: 'Payment processing failed',
      context: { orderId: 'ORD-2023-789', amount: '$150.00', error: 'Gateway timeout' }
    }
  ]
  
  // Mock users data
  users.value = [
    { id: 1, name: 'John Admin' },
    { id: 2, name: 'Jane Ops' },
    { id: 3, name: 'Bob Provider' }
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

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
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

.btn-outline-info {
  border: 1px solid #0ea5e9;
  color: #0ea5e9;
  background-color: transparent;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-outline-info:hover {
  background-color: #0ea5e9;
  color: white;
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

.grid {
  display: grid;
  gap: 1rem;
}

.table-responsive {
  overflow-x: auto;
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
  max-width: 600px;
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

.log-context {
  background-color: #1e293b;
  color: #f8fafc;
  padding: 1rem;
  border-radius: 0.375rem;
  overflow-x: auto;
  white-space: pre-wrap;
  font-family: 'Courier New', monospace;
  font-size: 0.875rem;
  margin: 0;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1rem 1.25rem;
  border-top: 1px solid #e2e8f0;
}
</style>