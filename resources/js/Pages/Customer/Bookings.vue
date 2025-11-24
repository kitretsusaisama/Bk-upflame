<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Bookings</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">My Bookings</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>➕</span> New Booking
        </button>
      </div>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Booking History</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Statuses</option>
            <option>Confirmed</option>
            <option>Pending</option>
            <option>Completed</option>
            <option>Cancelled</option>
          </select>
          <input type="text" placeholder="Search bookings..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['Booking ID', 'Service', 'Provider', 'Date & Time', 'Status', 'Amount', 'Actions']">
        <tr v-for="i in 10" :key="i">
          <td>
            <div class="booking-id">#BK{{ String(i).padStart(4, '0') }}</div>
          </td>
          <td>
            <div>Service {{ i }}</div>
          </td>
          <td>
            <div class="table-user">
              <div class="user-avatar">{{ getRandomInitial() }}</div>
              <div class="user-details">
                <div class="user-name">Provider {{ i }}</div>
              </div>
            </div>
          </td>
          <td>
            <div>{{ formatDate(subDays(new Date(), i)) }}</div>
            <div class="text-muted">{{ getRandomTime() }}</div>
          </td>
          <td>
            <Badge :type="getRandomStatusType()">
              {{ getRandomStatus() }}
            </Badge>
          </td>
          <td>
            <div class="amount">${{ (Math.random() * 100 + 50).toFixed(2) }}</div>
          </td>
          <td>
            <div class="table-actions">
              <a href="#" class="btn-icon" title="View Details">👁️</a>
              <a href="#" class="btn-icon" title="Cancel">❌</a>
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

function getRandomStatus() {
  const statuses = ['Confirmed', 'Pending', 'Completed', 'Cancelled']
  return statuses[Math.floor(Math.random() * statuses.length)]
}

function getRandomStatusType() {
  const types = ['success', 'warning', 'info', 'error']
  return types[Math.floor(Math.random() * types.length)]
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

.booking-id {
  font-weight: 600;
  color: #1e293b;
}

.amount {
  font-weight: 600;
  color: #1e293b;
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

.text-muted {
  color: #94a3b8;
  font-size: 0.875rem;
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
</style>