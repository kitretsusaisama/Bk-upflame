<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Tenant Dashboard</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>⚙️</span> Tenant Settings
        </button>
      </div>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="👥"
        :value="stats.total_users || '0'"
        label="Total Users"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="💼"
        :value="stats.total_providers || '0'"
        label="Service Providers"
        icon-class="stat-icon-info"
      />
      
      <StatCard 
        icon="📅"
        :value="stats.total_bookings || '0'"
        label="Active Bookings"
        icon-class="stat-icon-success"
      />
      
      <StatCard 
        icon="💰"
        :value="formatCurrency(stats.pending_workflows || 0)"
        label="Pending Workflows"
        icon-class="stat-icon-warning"
      />
    </div>
    
    <div class="dashboard-grid">
      <Card title="Recent Users" class="card-span-2">
        <template #actions>
          <button class="btn btn-secondary btn-sm">View All</button>
        </template>
        
        <Table :columns="userColumns" :data="users.data || []">
          <template #avatar="{ item }">
            <div class="avatar-small">
              {{ getUserInitials(item.profile) }}
            </div>
          </template>
          <template #name="{ item }">
            {{ item.profile?.first_name }} {{ item.profile?.last_name }}
          </template>
          <template #status="{ item }">
            <Badge :type="item.is_active ? 'success' : 'secondary'">
              {{ item.is_active ? 'Active' : 'Inactive' }}
            </Badge>
          </template>
          <template #actions="{ item }">
            <button class="btn btn-secondary btn-sm">View</button>
          </template>
        </Table>
      </Card>
      
      <Card title="Pending Workflows" class="card-span-1">
        <template #actions>
          <button class="btn btn-secondary btn-sm">View All</button>
        </template>
        
        <Table :columns="workflowColumns" :data="pendingWorkflows.data || []">
          <template #user="{ item }">
            <div class="user-cell">
              <div class="avatar-small">
                {{ getUserInitials(item.user?.profile) }}
              </div>
              <span>{{ item.user?.profile?.first_name }}</span>
            </div>
          </template>
          <template #status="{ item }">
            <Badge :type="getWorkflowStatusType(item.status)">
              {{ capitalize(item.status) }}
            </Badge>
          </template>
          <template #created_at="{ item }">
            {{ formatTimeAgo(item.created_at) }}
          </template>
        </Table>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const stats = ref(props.value.stats || {
  total_users: 0,
  total_providers: 0,
  total_bookings: 0,
  pending_workflows: 0
})

const users = ref(props.value.users || { data: [] })
const pendingWorkflows = ref(props.value.pendingWorkflows || { data: [] })

// Table columns
const userColumns = [
  { key: 'avatar', label: '', slot: true },
  { key: 'name', label: 'Name', slot: true },
  { key: 'email', label: 'Email' },
  { key: 'status', label: 'Status', slot: true },
  { key: 'actions', label: 'Actions', slot: true }
]

const workflowColumns = [
  { key: 'user', label: 'User', slot: true },
  { key: 'type', label: 'Type' },
  { key: 'status', label: 'Status', slot: true },
  { key: 'created_at', label: 'Created', slot: true }
]

// Methods for data formatting and display
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

const getUserInitials = (profile) => {
  if (profile && profile.first_name) {
    return profile.first_name.charAt(0).toUpperCase()
  }
  return 'U'
}

const capitalize = (str) => {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

const formatTimeAgo = (date) => {
  return new Date(date).toLocaleDateString()
}

const getWorkflowStatusType = (status) => {
  const statusTypes = {
    'pending': 'warning',
    'approved': 'success',
    'rejected': 'danger',
    'in_progress': 'info'
  }
  return statusTypes[status] || 'secondary'
}

// Log menu data for debugging
console.log('Menu data:', menu)
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.page-actions {
  display: flex;
  gap: 0.75rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
}

.card-span-2 {
  grid-column: span 2;
}

.card-span-1 {
  grid-column: span 1;
}

.avatar-small {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
}

.user-cell {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

/* Responsive styles */
@media (max-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .card-span-2,
  .card-span-1 {
    grid-column: span 1;
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .page-title {
    font-size: 1.5rem;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .user-cell {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
  }
}

@media (max-width: 480px) {
  .page-actions {
    width: 100%;
    flex-wrap: wrap;
  }
  
  .btn {
    flex: 1;
    min-width: 120px;
  }
}
</style>