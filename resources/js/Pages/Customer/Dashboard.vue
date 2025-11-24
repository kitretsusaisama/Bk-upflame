<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Welcome Back, {{ userFirstName }}!</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>➕</span> New Booking
        </button>
      </div>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="📅"
        :value="stats.total_bookings || '0'"
        label="Total Bookings"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="⏰"
        :value="stats.upcoming_bookings || '0'"
        label="Upcoming"
        icon-class="stat-icon-info"
      />
      
      <StatCard 
        icon="✅"
        :value="stats.completed_bookings || '0'"
        label="Completed"
        icon-class="stat-icon-success"
      />
      
      <StatCard 
        icon="💰"
        :value="`$${stats.total_spent || '0'}`"
        label="Total Spent"
        icon-class="stat-icon-warning"
      />
    </div>
    
    <div class="grid grid-2">
      <Card title="Your Upcoming Bookings" class="card-full-height">
        <template #actions>
          <a href="#" class="btn btn-sm btn-outline">View All</a>
        </template>
        
        <Table :headers="['Service', 'Provider', 'Date & Time', 'Status', 'Actions']">
          <tr v-for="booking in upcomingBookings" :key="booking.id">
            <td>
              <div class="service-name">{{ booking.service?.name || 'N/A' }}</div>
              <div class="text-muted">{{ booking.booking_reference }}</div>
            </td>
            <td>
              <div class="table-user">
                <div class="user-avatar">{{ getProviderInitials(booking.provider) }}</div>
                <div class="user-details">
                  <div class="user-name">{{ booking.provider?.first_name || 'Provider' }}</div>
                  <div class="user-email">{{ booking.provider?.email || 'N/A' }}</div>
                </div>
              </div>
            </td>
            <td>
              <div>{{ formatDate(booking.scheduled_at) }}</div>
              <div class="text-muted">{{ formatTime(booking.scheduled_at) }}</div>
            </td>
            <td>
              <Badge :type="getBookingStatusType(booking.status)">
                {{ capitalize(booking.status) }}
              </Badge>
            </td>
            <td>
              <div class="table-actions">
                <a href="#" class="btn-icon" title="View Details">👁️</a>
                <a href="#" class="btn-icon" title="Cancel">❌</a>
              </div>
            </td>
          </tr>
          <tr v-if="upcomingBookings.length === 0">
            <td colspan="5" class="text-center">
              <div class="empty-state">
                <p>No upcoming bookings</p>
                <button class="btn btn-sm btn-primary">Book Now</button>
              </div>
            </td>
          </tr>
        </Table>
      </Card>
      
      <Card title="Available Services">
        <div class="services-grid">
          <div v-for="i in 4" :key="i" class="service-card">
            <div class="service-icon">🔧</div>
            <div class="service-name">Service {{ i }}</div>
            <div class="service-price">$99.00</div>
            <button class="btn btn-sm btn-primary btn-block">Book Now</button>
          </div>
        </div>
      </Card>
    </div>
    
    <div class="grid grid-1">
      <Card title="Recent Bookings">
        <div class="booking-history">
          <div v-for="i in 3" :key="i" class="booking-history-item">
            <div class="booking-icon completed">✅</div>
            <div class="booking-details">
              <div class="booking-service">Service {{ i }}</div>
              <div class="booking-meta">
                <span>Provider: John Doe</span>
                <span class="meta-sep">•</span>
                <span>{{ formatDate(subDays(new Date(), i * 5)) }}</span>
              </div>
            </div>
            <div class="booking-actions">
              <Badge type="success">Completed</Badge>
              <button class="btn btn-sm btn-outline">View Details</button>
              <button class="btn btn-sm btn-primary">Rebook</button>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { computed } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

const { props } = usePage()
const stats = computed(() => props.value.stats || {})
const upcomingBookings = computed(() => props.value.upcomingBookings || [])
const user = computed(() => props.value.auth?.user || {})

const userFirstName = computed(() => {
  if (user.value.profile?.first_name) {
    return user.value.profile.first_name
  }
  if (user.value.name) {
    return user.value.name.split(' ')[0]
  }
  return 'Customer'
})

// Helper functions
function getProviderInitials(provider) {
  if (provider?.first_name) {
    return provider.first_name.charAt(0).toUpperCase()
  }
  return 'P'
}

function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric' 
  })
}

function formatTime(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleTimeString('en-US', { 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true
  })
}

function getBookingStatusType(status) {
  const statusTypes = {
    confirmed: 'success',
    pending: 'warning',
    completed: 'success',
    cancelled: 'error'
  }
  return statusTypes[status] || 'default'
}

function capitalize(str) {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
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

.grid-1 {
  grid-template-columns: 1fr;
}

.card-full-height {
  height: 100%;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}

.service-card {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 1rem;
  text-align: center;
  background-color: #f8fafc;
}

.service-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.service-name {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.service-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 1rem;
}

.btn-block {
  width: 100%;
}

.table-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
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

.user-details {
  min-width: 0;
}

.user-name {
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-email {
  font-size: 0.875rem;
  color: #64748b;
}

.text-muted {
  color: #94a3b8;
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

.empty-state {
  padding: 2rem;
  text-align: center;
}

.empty-state p {
  margin-bottom: 1rem;
  color: #64748b;
}

.booking-history {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.booking-history-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
}

.booking-icon {
  font-size: 1.5rem;
}

.booking-icon.completed {
  color: #10b981;
}

.booking-details {
  flex: 1;
  min-width: 0;
}

.booking-service {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.booking-meta {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #64748b;
}

.meta-sep {
  color: #cbd5e1;
}

.booking-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .booking-history-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .booking-actions {
    align-self: stretch;
    justify-content: flex-end;
  }
}
</style>