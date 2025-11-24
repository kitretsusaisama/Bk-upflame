<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Super Admin Dashboard</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>➕</span> Create Tenant
        </button>
      </div>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="🏢"
        :value="stats.total_tenants || '0'"
        label="Total Tenants"
        change="+12%"
        change-type="positive"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="👥"
        :value="stats.total_users || '0'"
        label="Total Users"
        change="+8%"
        change-type="positive"
        icon-class="stat-icon-success"
      />
      
      <StatCard 
        icon="📊"
        :value="stats.active_sessions || '0'"
        label="Active Sessions"
        icon-class="stat-icon-info"
      />
      
      <StatCard 
        icon="💰"
        :value="formatCurrency(stats.revenue || 0)"
        label="Total Revenue"
        change="+23%"
        change-type="positive"
        icon-class="stat-icon-warning"
      />
    </div>
    
    <div class="grid grid-2">
      <Card title="Recent Tenants" class="card-spacing">
        <template #actions>
          <a href="#" class="btn btn-sm btn-outline">View All</a>
        </template>
        
        <template #body>
          <div class="table-responsive">
            <Table :headers="['Tenant', 'Domain', 'Status', 'Created', 'Actions']">
              <tr v-for="tenant in tenants.data" :key="tenant.id">
                <td>
                  <div class="table-user">
                    <div class="user-avatar">{{ getInitials(tenant.name) }}</div>
                    <div class="user-details">
                      <div class="user-name">{{ tenant.name }}</div>
                      <div class="user-email">{{ tenant.domain }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ tenant.domain }}</td>
                <td>
                  <Badge :type="tenant.status === 'active' ? 'success' : 'secondary'">
                    {{ capitalize(tenant.status) }}
                  </Badge>
                </td>
                <td>{{ formatTimeAgo(tenant.created_at) }}</td>
                <td>
                  <div class="table-actions">
                    <a href="#" class="btn-icon" title="Edit">✏️</a>
                    <a href="#" class="btn-icon" title="View">👁️</a>
                    <a href="#" class="btn-icon text-danger" title="Delete">🗑️</a>
                  </div>
                </td>
              </tr>
              
              <tr v-if="tenants.data.length === 0">
                <td colspan="5" class="text-center">No tenants found</td>
              </tr>
            </Table>
          </div>
        </template>
      </Card>
      
      <Card title="System Status" class="card-spacing">
        <template #body>
          <div class="status-list">
            <div class="status-item">
              <div class="status-label">Database</div>
              <div class="status-value">
                <Badge type="success">Online</Badge>
              </div>
            </div>
            <div class="status-item">
              <div class="status-label">Cache</div>
              <div class="status-value">
                <Badge type="success">Active</Badge>
              </div>
            </div>
            <div class="status-item">
              <div class="status-label">Queue</div>
              <div class="status-value">
                <Badge type="success">Running</Badge>
              </div>
            </div>
            <div class="status-item">
              <div class="status-label">Storage</div>
              <div class="status-value">
                <div class="progress-container">
                  <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: '65%' }"></div>
                  </div>
                  <span class="progress-text">65% used</span>
                </div>
              </div>
            </div>
          </div>
        </template>
      </Card>
    </div>
    
    <div class="grid grid-1">
      <Card title="System Activity" class="card-spacing">
        <template #body>
          <div class="activity-timeline">
            <div class="activity-item" v-for="(activity, index) in activities" :key="index">
              <div class="activity-icon" :class="getActivityIconClass(activity.type)">
                {{ activity.icon }}
              </div>
              <div class="activity-content">
                <div class="activity-title">{{ activity.title }}</div>
                <div class="activity-time">{{ activity.time }}</div>
              </div>
            </div>
          </div>
        </template>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'
import StatCard from '@/Components/UI/StatCard.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const stats = ref(props.value.stats || {
  total_tenants: 0,
  total_users: 0,
  active_sessions: 0,
  revenue: 0
})

const tenants = ref(props.value.tenants || { data: [] })
const activities = ref([])

// Methods
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

const getInitials = (name) => {
  return name ? name.charAt(0).toUpperCase() : 'U'
}

const capitalize = (str) => {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

const formatTimeAgo = (date) => {
  return new Date(date).toLocaleDateString()
}

const getActivityIconClass = (type) => {
  const classes = {
    'success': 'activity-icon-success',
    'info': 'activity-icon-info',
    'warning': 'activity-icon-warning',
    'danger': 'activity-icon-danger'
  }
  return classes[type] || 'activity-icon-secondary'
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
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
  border: 1px solid #3b82f6;
}

.btn-primary:hover {
  background-color: #2563eb;
  border-color: #2563eb;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-1 {
  grid-template-columns: 1fr;
}

.grid-2 {
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

.card-spacing {
  margin-bottom: 1.5rem;
}

.table-responsive {
  overflow-x: auto;
}

.table-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background-color: #e0f2fe;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: #0369a1;
}

.user-details {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 500;
  color: #1e293b;
}

.user-email {
  font-size: 0.875rem;
  color: #64748b;
}

.table-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  padding: 0.25rem;
  border-radius: 0.25rem;
  color: #64748b;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-icon:hover {
  background-color: #f1f5f9;
  color: #1e293b;
}

.text-danger {
  color: #ef4444;
}

.status-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.status-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
}

.status-label {
  font-weight: 500;
  color: #334155;
}

.status-value {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.progress-container {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.progress-bar {
  width: 100%;
  height: 0.5rem;
  background-color: #e2e8f0;
  border-radius: 0.25rem;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background-color: #3b82f6;
  border-radius: 0.25rem;
}

.progress-text {
  font-size: 0.75rem;
  color: #64748b;
  text-align: right;
}

.activity-timeline {
  position: relative;
  padding-left: 2rem;
}

.activity-timeline::before {
  content: '';
  position: absolute;
  left: 1rem;
  top: 0;
  bottom: 0;
  width: 2px;
  background-color: #e2e8f0;
}

.activity-item {
  position: relative;
  margin-bottom: 1.5rem;
}

.activity-icon {
  position: absolute;
  left: -2.5rem;
  top: 0;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.activity-icon-success {
  background-color: #dcfce7;
  color: #16a34a;
}

.activity-icon-info {
  background-color: #dbeafe;
  color: #2563eb;
}

.activity-icon-warning {
  background-color: #fef3c7;
  color: #d97706;
}

.activity-icon-danger {
  background-color: #fee2e2;
  color: #dc2626;
}

.activity-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.activity-title {
  font-weight: 500;
  color: #1e293b;
}

.activity-time {
  font-size: 0.875rem;
  color: #64748b;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-outline {
  border: 1px solid #cbd5e1;
  color: #64748b;
  background-color: transparent;
}

.btn-outline:hover {
  background-color: #f1f5f9;
  color: #1e293b;
}
</style>