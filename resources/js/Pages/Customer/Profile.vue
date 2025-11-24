<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Profile</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">My Profile</h1>
    </div>
    
    <div class="grid grid-2">
      <Card title="Profile Information">
        <form @submit.prevent="updateProfile">
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-input" v-model="profileForm.name" placeholder="Enter your full name">
          </div>
          
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-input" v-model="profileForm.email" placeholder="Enter your email">
          </div>
          
          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" class="form-input" v-model="profileForm.phone" placeholder="Enter your phone number">
          </div>
          
          <div class="form-group">
            <label class="form-label">Bio</label>
            <textarea class="form-textarea" v-model="profileForm.bio" rows="4" placeholder="Tell us about yourself"></textarea>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Profile</button>
          </div>
        </form>
      </Card>
      
      <div class="profile-sidebar">
        <Card title="Profile Picture">
          <div class="profile-picture-container">
            <div class="profile-picture">
              <div class="user-initials">{{ getUserInitials() }}</div>
            </div>
            <button class="btn btn-outline btn-sm">Change Picture</button>
          </div>
        </Card>
        
        <Card title="Account Statistics">
          <div class="stats-list">
            <div class="stat-item">
              <div class="stat-label">Member Since</div>
              <div class="stat-value">Jan 12, 2023</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Total Bookings</div>
              <div class="stat-value">24</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Completed</div>
              <div class="stat-value">22</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Last Booking</div>
              <div class="stat-value">Dec 15, 2023</div>
            </div>
          </div>
        </Card>
      </div>
    </div>
    
    <Card title="Change Password">
      <form @submit.prevent="changePassword">
        <div class="grid grid-3">
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-input" v-model="passwordForm.current" placeholder="Enter current password">
          </div>
          
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" class="form-input" v-model="passwordForm.new" placeholder="Enter new password">
          </div>
          
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-input" v-model="passwordForm.confirm" placeholder="Confirm new password">
          </div>
        </div>
        
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Change Password</button>
        </div>
      </form>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Form data
const profileForm = reactive({
  name: 'John Doe',
  email: 'john.doe@example.com',
  phone: '+1 (555) 123-4567',
  bio: 'I am a professional customer who enjoys using this service.'
})

const passwordForm = reactive({
  current: '',
  new: '',
  confirm: ''
})

// Helper function to get user initials
function getUserInitials() {
  if (profileForm.name) {
    return profileForm.name.charAt(0).toUpperCase()
  }
  return 'U'
}

// Form submission handlers
function updateProfile() {
  console.log('Updating profile:', profileForm)
  // In a real app, you would send this data to your backend
  alert('Profile updated successfully!')
}

function changePassword() {
  if (passwordForm.new !== passwordForm.confirm) {
    alert('New passwords do not match!')
    return
  }
  
  console.log('Changing password:', passwordForm)
  // In a real app, you would send this data to your backend
  alert('Password changed successfully!')
  
  // Reset form
  passwordForm.current = ''
  passwordForm.new = ''
  passwordForm.confirm = ''
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

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.grid-3 {
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
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

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.form-actions {
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.profile-sidebar {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.profile-picture-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
}

.profile-picture {
  width: 100px;
  height: 100px;
  border-radius: 9999px;
  background-color: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-initials {
  font-size: 2rem;
  font-weight: 600;
  color: white;
}

.stats-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stat-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
}

.stat-item:last-child {
  border-bottom: none;
}

.stat-label {
  color: #64748b;
}

.stat-value {
  font-weight: 500;
  color: #1e293b;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .grid-3 {
    grid-template-columns: 1fr;
  }
}
</style>