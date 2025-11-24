<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Provider Dashboard</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>📅</span> My Schedule
        </button>
      </div>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="📅"
        value="24"
        label="Upcoming Bookings"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="✅"
        value="18"
        label="Completed Today"
        icon-class="stat-icon-success"
      />
      
      <StatCard 
        icon="⭐"
        value="4.8"
        label="Average Rating"
        icon-class="stat-icon-warning"
      />
      
      <StatCard 
        icon="💰"
        value="$1,240"
        label="Earnings (30 days)"
        icon-class="stat-icon-info"
      />
    </div>
    
    <div class="grid grid-2">
      <Card title="Today's Schedule">
        <Table :headers="['Time', 'Customer', 'Service', 'Status', 'Actions']">
          <tr v-for="i in 5" :key="i">
            <td>{{ getRandomTime() }}</td>
            <td>
              <div class="table-user">
                <div class="user-avatar">{{ getRandomInitial() }}</div>
                <div class="user-details">
                  <div class="user-name">Customer {{ i }}</div>
                </div>
              </div>
            </td>
            <td>Service {{ i }}</td>
            <td>
              <Badge v-if="i % 3 === 0" type="success">Confirmed</Badge>
              <Badge v-else-if="i % 3 === 1" type="warning">Pending</Badge>
              <Badge v-else type="info">In Progress</Badge>
            </td>
            <td>
              <div class="table-actions">
                <button class="btn-icon" title="View">👁️</button>
                <button class="btn-icon" title="Complete">✅</button>
              </div>
            </td>
          </tr>
        </Table>
      </Card>
      
      <Card title="Recent Reviews">
        <div class="reviews-list">
          <div v-for="i in 3" :key="i" class="review-item">
            <div class="review-header">
              <div class="reviewer">
                <div class="reviewer-avatar">{{ getRandomInitial() }}</div>
                <div class="reviewer-info">
                  <div class="reviewer-name">Customer {{ i }}</div>
                  <div class="review-date">{{ formatDate(subDays(new Date(), i)) }}</div>
                </div>
              </div>
              <div class="review-rating">
                <span v-for="j in 5" :key="j" class="star" :class="{ 'filled': j <= 5 - i }">⭐</span>
              </div>
            </div>
            <div class="review-content">
              Great service! Provider was punctual and professional. Highly recommend!
            </div>
          </div>
        </div>
      </Card>
    </div>
    
    <Card title="Quick Actions">
      <div class="quick-actions-grid">
        <button class="quick-action">
          <span class="action-icon">📅</span>
          <span class="action-label">View Schedule</span>
        </button>
        
        <button class="quick-action">
          <span class="action-icon">👥</span>
          <span class="action-label">My Customers</span>
        </button>
        
        <button class="quick-action">
          <span class="action-icon">💼</span>
          <span class="action-label">Services</span>
        </button>
        
        <button class="quick-action">
          <span class="action-icon">⚙️</span>
          <span class="action-label">Settings</span>
        </button>
        
        <button class="quick-action">
          <span class="action-icon">💰</span>
          <span class="action-label">Payments</span>
        </button>
        
        <button class="quick-action">
          <span class="action-icon">📊</span>
          <span class="action-label">Reports</span>
        </button>
      </div>
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
  const hours = Math.floor(Math.random() * 8) + 9
  const minutes = Math.floor(Math.random() * 60)
  return `${hours}:${minutes.toString().padStart(2, '0')} AM`
}

function formatDate(date) {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric' 
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

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.review-item {
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background-color: #f8fafc;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.reviewer {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.reviewer-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
  background-color: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
}

.reviewer-info {
  min-width: 0;
}

.reviewer-name {
  font-weight: 500;
  color: #1e293b;
}

.review-date {
  font-size: 0.875rem;
  color: #64748b;
}

.review-rating {
  display: flex;
  gap: 0.25rem;
  color: #e2e8f0;
}

.review-rating .filled {
  color: #f59e0b;
}

.review-content {
  color: #334155;
  line-height: 1.5;
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
}

.quick-action {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 1.5rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background-color: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.quick-action:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-color: #cbd5e1;
}

.action-icon {
  font-size: 1.5rem;
}

.action-label {
  font-weight: 500;
  color: #1e293b;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>