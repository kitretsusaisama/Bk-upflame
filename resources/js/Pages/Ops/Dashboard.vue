<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Ops</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Operations Dashboard</h1>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="📋"
        value="24"
        label="Pending Approvals"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="🔄"
        value="12"
        label="Active Workflows"
        icon-class="stat-icon-info"
      />
      
      <StatCard 
        icon="⚠️"
        value="3"
        label="Alerts"
        icon-class="stat-icon-warning"
      />
      
      <StatCard 
        icon="✅"
        value="156"
        label="Completed Today"
        icon-class="stat-icon-success"
      />
    </div>
    
    <div class="grid grid-2">
      <Card title="Recent Activity">
        <Table :headers="['User', 'Action', 'Resource', 'Time']">
          <tr v-for="i in 5" :key="i">
            <td>
              <div class="table-user">
                <div class="user-avatar">{{ getRandomInitial() }}</div>
                <div class="user-details">
                  <div class="user-name">User {{ i }}</div>
                </div>
              </div>
            </td>
            <td>
              <span v-if="i % 3 === 0">Approved</span>
              <span v-else-if="i % 3 === 1">Rejected</span>
              <span v-else>Updated</span>
            </td>
            <td>
              <span v-if="i % 2 === 0">Booking #BK{{ String(i * 12).padStart(4, '0') }}</span>
              <span v-else>Workflow {{ i }}</span>
            </td>
            <td>{{ getRandomTime() }}</td>
          </tr>
        </Table>
      </Card>
      
      <Card title="System Status">
        <div class="system-status">
          <div class="status-item">
            <div class="status-icon status-success">✅</div>
            <div class="status-details">
              <div class="status-label">Database</div>
              <div class="status-value">Operational</div>
            </div>
          </div>
          
          <div class="status-item">
            <div class="status-icon status-success">✅</div>
            <div class="status-details">
              <div class="status-label">API</div>
              <div class="status-value">Operational</div>
            </div>
          </div>
          
          <div class="status-item">
            <div class="status-icon status-warning">⚠️</div>
            <div class="status-details">
              <div class="status-label">Email Service</div>
              <div class="status-value">Degraded</div>
            </div>
          </div>
          
          <div class="status-item">
            <div class="status-icon status-success">✅</div>
            <div class="status-details">
              <div class="status-label">Storage</div>
              <div class="status-value">Operational</div>
            </div>
          </div>
        </div>
      </Card>
    </div>
    
    <Card title="Pending Approvals">
      <Table :headers="['Request ID', 'Type', 'Submitted By', 'Submitted At', 'Status', 'Actions']">
        <tr v-for="i in 5" :key="i">
          <td>#REQ{{ String(i * 7 + 5).padStart(4, '0') }}</td>
          <td>
            <span v-if="i % 3 === 0">Booking Cancellation</span>
            <span v-else-if="i % 3 === 1">Service Update</span>
            <span v-else>Account Access</span>
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
            <Badge type="warning">Pending</Badge>
          </td>
          <td>
            <div class="table-actions">
              <button class="btn-icon" title="View">👁️</button>
              <button class="btn-icon" title="Approve">✅</button>
              <button class="btn-icon" title="Reject">❌</button>
            </div>
          </td>
        </tr>
      </Table>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Helper functions
function getRandomInitial() {
  const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  return letters.charAt(Math.floor(Math.random() * letters.length))
}

function getRandomTime() {
  const hours = Math.floor(Math.random() * 12) + 1
  const minutes = Math.floor(Math.random() * 60)
  const ampm = Math.random() > 0.5 ? 'AM' : 'PM'
  return `${hours}:${minutes.toString().padStart(2, '0')} ${ampm}`
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
</script>

<style scoped>
.page-header {
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: repeat(2, 1fr);
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

.system-status {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem;
  border-radius: 0.375rem;
  background-color: #f8fafc;
}

.status-icon {
  font-size: 1.25rem;
}

.status-success {
  color: #10b981;
}

.status-warning {
  color: #f59e0b;
}

.status-error {
  color: #ef4444;
}

.status-details {
  flex: 1;
}

.status-label {
  font-weight: 500;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.status-value {
  font-size: 0.875rem;
  color: #64748b;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>