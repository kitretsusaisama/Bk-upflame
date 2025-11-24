<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Schedule</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">My Schedule</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showAddAvailabilityModal = true">
          <span>➕</span> Add Availability
        </button>
      </div>
    </div>
    
    <div class="grid grid-2">
      <Card title="Calendar View">
        <div class="calendar-header">
          <button class="btn btn-outline btn-sm">Previous</button>
          <div class="calendar-title">November 2023</div>
          <button class="btn btn-outline btn-sm">Next</button>
        </div>
        
        <div class="calendar-grid">
          <div class="calendar-day-header" v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
            {{ day }}
          </div>
          
          <div v-for="i in 30" :key="i" class="calendar-day" :class="{ 'today': i === 15, 'has-events': i % 5 === 0 }">
            <div class="day-number">{{ i }}</div>
            <div v-if="i % 5 === 0" class="day-events">
              <div class="event-dot"></div>
              <div class="event-dot"></div>
            </div>
          </div>
        </div>
      </Card>
      
      <div class="schedule-sidebar">
        <Card title="Availability Settings">
          <form @submit.prevent="updateAvailability">
            <div class="form-group">
              <label class="form-label">Working Days</label>
              <div class="days-selection">
                <label v-for="day in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" :key="day" class="day-option">
                  <input type="checkbox" class="day-checkbox" :checked="day !== 'Sun'">
                  <span class="day-label">{{ day }}</span>
                </label>
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">Working Hours</label>
              <div class="time-range">
                <input type="time" class="time-input" value="09:00">
                <span>to</span>
                <input type="time" class="time-input" value="17:00">
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">Break Time</label>
              <div class="time-range">
                <input type="time" class="time-input" value="12:00">
                <span>to</span>
                <input type="time" class="time-input" value="13:00">
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">Appointment Duration</label>
              <select class="form-select">
                <option>30 minutes</option>
                <option selected>60 minutes</option>
                <option>90 minutes</option>
                <option>120 minutes</option>
              </select>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Update Availability</button>
            </div>
          </form>
        </Card>
        
        <Card title="Upcoming Appointments">
          <div class="appointments-list">
            <div v-for="i in 3" :key="i" class="appointment-item">
              <div class="appointment-time">{{ getRandomTime() }}</div>
              <div class="appointment-details">
                <div class="appointment-customer">Customer {{ i }}</div>
                <div class="appointment-service">Service {{ i }}</div>
              </div>
              <Badge type="success">Confirmed</Badge>
            </div>
          </div>
        </Card>
      </div>
    </div>
    
    <!-- Add Availability Modal -->
    <div v-if="showAddAvailabilityModal" class="modal-overlay" @click="showAddAvailabilityModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add Availability</h3>
          <button class="modal-close" @click="showAddAvailabilityModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="addAvailability">
            <div class="form-group">
              <label class="form-label">Date</label>
              <input type="date" class="form-input" v-model="availabilityForm.date">
            </div>
            
            <div class="form-group">
              <label class="form-label">Start Time</label>
              <input type="time" class="form-input" v-model="availabilityForm.startTime">
            </div>
            
            <div class="form-group">
              <label class="form-label">End Time</label>
              <input type="time" class="form-input" v-model="availabilityForm.endTime">
            </div>
            
            <div class="form-group">
              <label class="form-label">Repeat</label>
              <select class="form-select" v-model="availabilityForm.repeat">
                <option value="none">No Repeat</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showAddAvailabilityModal = false">Cancel</button>
          <button class="btn btn-primary" @click="addAvailability">Add Availability</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Badge from '@/Components/UI/Badge.vue'

// Modal state
const showAddAvailabilityModal = ref(false)

// Form data
const availabilityForm = reactive({
  date: '',
  startTime: '',
  endTime: '',
  repeat: 'none'
})

// Helper functions
function getRandomTime() {
  const hours = Math.floor(Math.random() * 8) + 9
  const minutes = Math.floor(Math.random() * 60)
  return `${hours}:${minutes.toString().padStart(2, '0')} AM`
}

// Form submission handlers
function updateAvailability() {
  console.log('Updating availability')
  alert('Availability updated successfully!')
}

function addAvailability() {
  console.log('Adding availability:', availabilityForm)
  alert('Availability added successfully!')
  showAddAvailabilityModal.value = false
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

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.calendar-title {
  font-weight: 600;
  color: #1e293b;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.25rem;
}

.calendar-day-header {
  text-align: center;
  padding: 0.5rem;
  font-weight: 600;
  color: #64748b;
  font-size: 0.875rem;
}

.calendar-day {
  aspect-ratio: 1;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  padding: 0.25rem;
  position: relative;
  cursor: pointer;
  transition: all 0.2s ease;
}

.calendar-day:hover {
  background-color: #f1f5f9;
}

.calendar-day.today {
  background-color: #dbeafe;
  border-color: #3b82f6;
}

.calendar-day.has-events {
  background-color: #f0f9ff;
}

.day-number {
  font-size: 0.875rem;
  color: #1e293b;
}

.day-events {
  display: flex;
  gap: 0.125rem;
  position: absolute;
  bottom: 0.25rem;
  left: 50%;
  transform: translateX(-50%);
}

.event-dot {
  width: 0.375rem;
  height: 0.375rem;
  border-radius: 9999px;
  background-color: #3b82f6;
}

.schedule-sidebar {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.days-selection {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0.5rem;
}

.day-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.day-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 0.25rem;
  border: 1px solid #cbd5e1;
  cursor: pointer;
}

.day-label {
  font-size: 0.875rem;
  color: #1e293b;
}

.time-range {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.time-input {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.form-select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-actions {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.appointments-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.appointment-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  background-color: #f8fafc;
}

.appointment-time {
  font-weight: 600;
  color: #1e293b;
  min-width: 70px;
}

.appointment-details {
  flex: 1;
}

.appointment-customer {
  font-weight: 500;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.appointment-service {
  font-size: 0.875rem;
  color: #64748b;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #94a3b8;
  padding: 0;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #64748b;
}

.modal-body {
  padding: 1.5rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .days-selection {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>