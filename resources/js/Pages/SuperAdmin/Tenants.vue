<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Tenants</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Manage Tenants</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="openCreateModal">
          Create New Tenant
        </button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input 
            type="text" 
            class="form-control" 
            placeholder="Search tenants..."
            v-model="searchTerm"
          >
          <select class="form-control" v-model="statusFilter">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </template>
      
      <template #body>
        <div class="table-responsive">
          <Table :headers="['Name', 'Domain', 'Status', 'Subscription', 'Actions']">
            <tr v-for="tenant in filteredTenants" :key="tenant.id">
              <td>{{ tenant.name }}</td>
              <td>{{ tenant.domain }}</td>
              <td>
                <Badge :type="getStatusBadgeType(tenant.status)">
                  {{ capitalize(tenant.status) }}
                </Badge>
              </td>
              <td>{{ tenant.subscription }}</td>
              <td>
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button 
                  class="btn btn-sm" 
                  :class="tenant.status === 'active' ? 'btn-danger' : 'btn-success'"
                  @click="toggleTenantStatus(tenant)"
                >
                  {{ tenant.status === 'active' ? 'Suspend' : 'Activate' }}
                </button>
              </td>
            </tr>
            
            <tr v-if="filteredTenants.length === 0">
              <td colspan="5" class="text-center">No tenants found</td>
            </tr>
          </Table>
        </div>
        
        <div class="pagination-container" v-if="filteredTenants.length > 0">
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
    
    <!-- Create Tenant Modal -->
    <div class="modal" :class="{ 'show': showCreateModal }" v-if="showCreateModal">
      <div class="modal-overlay" @click="closeCreateModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create New Tenant</h5>
          <button type="button" class="close" @click="closeCreateModal">&times;</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createTenant">
            <div class="form-group">
              <label for="tenantName">Tenant Name</label>
              <input 
                type="text" 
                class="form-control" 
                id="tenantName" 
                v-model="newTenant.name"
                required
              >
            </div>
            <div class="form-group">
              <label for="tenantDomain">Domain</label>
              <input 
                type="text" 
                class="form-control" 
                id="tenantDomain" 
                placeholder="e.g., acme.example.com"
                v-model="newTenant.domain"
                required
              >
            </div>
            <div class="form-group">
              <label for="tenantSubscription">Subscription Tier</label>
              <select class="form-control" id="tenantSubscription" v-model="newTenant.subscription">
                <option>Free</option>
                <option>Basic</option>
                <option>Premium</option>
                <option>Enterprise</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeCreateModal">Cancel</button>
          <button type="button" class="btn btn-primary" @click="createTenant">Create Tenant</button>
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
const tenants = ref([])
const searchTerm = ref('')
const statusFilter = ref('')
const currentPage = ref(1)
const totalPages = ref(1)
const showCreateModal = ref(false)

const newTenant = ref({
  name: '',
  domain: '',
  subscription: 'Basic'
})

// Computed
const filteredTenants = computed(() => {
  let result = tenants.value
  
  // Apply search filter
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase()
    result = result.filter(tenant => 
      tenant.name.toLowerCase().includes(term) || 
      tenant.domain.toLowerCase().includes(term)
    )
  }
  
  // Apply status filter
  if (statusFilter.value) {
    result = result.filter(tenant => tenant.status === statusFilter.value)
  }
  
  return result
})

// Methods
const getStatusBadgeType = (status) => {
  const badgeTypes = {
    'active': 'success',
    'pending': 'warning',
    'suspended': 'danger'
  }
  return badgeTypes[status] || 'secondary'
}

const capitalize = (str) => {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

const openCreateModal = () => {
  showCreateModal.value = true
}

const closeCreateModal = () => {
  showCreateModal.value = false
  newTenant.value = {
    name: '',
    domain: '',
    subscription: 'Basic'
  }
}

const createTenant = () => {
  // In a real implementation, this would make an API call
  const tenant = {
    id: tenants.value.length + 1,
    name: newTenant.value.name,
    domain: newTenant.value.domain,
    status: 'pending',
    subscription: newTenant.value.subscription
  }
  
  tenants.value.push(tenant)
  closeCreateModal()
}

const toggleTenantStatus = (tenant) => {
  // In a real implementation, this would make an API call
  tenant.status = tenant.status === 'active' ? 'suspended' : 'active'
}

const changePage = (page) => {
  currentPage.value = page
}

// Mock data for demonstration
onMounted(() => {
  tenants.value = [
    {
      id: 1,
      name: 'Acme Corp',
      domain: 'acme.example.com',
      status: 'active',
      subscription: 'Premium'
    },
    {
      id: 2,
      name: 'Globex Inc',
      domain: 'globex.example.com',
      status: 'pending',
      subscription: 'Basic'
    },
    {
      id: 3,
      name: 'Wayne Enterprises',
      domain: 'wayne.example.com',
      status: 'active',
      subscription: 'Enterprise'
    }
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

.form-control {
  display: block;
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
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
</style>