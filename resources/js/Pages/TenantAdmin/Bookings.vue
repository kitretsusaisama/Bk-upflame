<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Bookings</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Booking Management</h1>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Bookings</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Statuses</option>
            <option>Confirmed</option>
            <option>Pending</option>
            <option>Completed</option>
            <option>Cancelled</option>
          </select>
          <select class="form-select">
            <option>All Providers</option>
            <option>Provider 1</option>
            <option>Provider 2</option>
            <option>Provider 3</option>
          </select>
          <input type="text" placeholder="Search bookings..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['Booking ID', 'Service', 'Customer', 'Provider', 'Date & Time', 'Status', 'Amount', 'Actions']">
        <tr v-for="i in 10" :key="i">
          <td>#BK{{ String(i * 7 + 5).padStart(4, '0') }}</td>
          <td>Service {{ i }}</td>
          <td>
            <div class="table-user">
              <div class="user-avatar">{{ getRandomInitial() }}</div>
              <div class="user-details">
                <div class="user-name">Customer {{ i }}</div>
              </div>
            </div>
          </td>
          <td>Provider {{ i }}</td>
          <td>{{ formatDate(subDays(new Date(), i)) }} at {{ getRandomTime() }}</td>
          <td>
            <Badge v-if="i % 4 === 0" type="success">Confirmed</Badge>
            <Badge v-else-if="i % 4 === 1" type="warning">Pending</Badge>
            <Badge v-else-if="i % 4 === 2" type="info">Completed</Badge>
            <Badge v-else type="error">Cancelled</Badge>
          </td>
          <td>${{ (Math.random() * 100 + 50).toFixed(2) }}</td>
          <td>
            <div class="table-actions">
              <button class="btn-icon" title="View">👁️</button>
              <button class="btn-icon" title="Edit">✏️</button>
              <button class="btn-icon" title="Cancel">❌</button>
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
    
    <div class="stats-grid">
      <Card title="Booking Statistics">
        <div class="stats-content">
          <div class="stat-item">
            <div class="stat-value">142</div>
            <div class="stat-label">Total Bookings</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">32</div>
            <div class="stat-label">Pending</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">89</div>
            <div class="stat-label">Confirmed</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">21</div>
            <div class="stat-label">Cancelled</div>
          </div>
        </div>
      </Card>
      
      <Card title="Revenue Overview">
        <div class="revenue-chart">
          <div class="chart-placeholder">
            <div class="chart-title">Monthly Revenue</div>
            <div class="chart-container">
              <!-- In a real app, this would be a chart component -->
              <div class="chart-bars">
                <div v-for="i in 6" :key="i" class="chart-bar" :style="{ height: `${20 + i * 10}%` }"></div>
              </div>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

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

function getRandomTime() {
  const hours = Math.floor(Math.random() * 12) + 1
  const minutes = Math.floor(Math.random() * 60)
  const ampm = Math.random() > 0.5 ? 'AM' : 'PM'
  return `${hours}:${minutes.toString().padStart(2, '0')} ${ampm}`
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.stats-content {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.stat-item {
  text-align: center;
  padding: 1rem 0;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.875rem;
  color: #64748b;
}

.revenue-chart {
  padding: 1rem 0;
}

.chart-placeholder {
  text-align: center;
}

.chart-title {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 1rem;
}

.chart-container {
  height: 200px;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 0.5rem;
  padding: 0 1rem;
}

.chart-bars {
  display: flex;
  align-items: flex-end;
  gap: 0.5rem;
  height: 100%;
}

.chart-bar {
  width: 30px;
  background-color: #3b82f6;
  border-radius: 4px 4px 0 0;
  min-height: 10px;
}

@media (max-width: 768px) {
  .stats-content {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .card-actions {
    flex-direction: column;
  }
}
</style>