<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Profile</span>
    </template>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-1">
        <Card>
          <template #body>
            <div class="text-center mb-6">
              <div class="user-avatar mx-auto mb-4">
                {{ getInitials(user.email || 'U') }}
              </div>
              <h5 class="text-xl font-semibold">{{ user.email || 'User' }}</h5>
              <p class="text-muted">{{ userRole }}</p>
            </div>
            
            <div class="nav flex-column" role="tablist">
              <a 
                class="nav-link" 
                :class="{ 'active': activeTab === 'profile' }"
                href="#" 
                @click.prevent="activeTab = 'profile'"
              >
                <i class="ti ti-user mr-2"></i>Profile Information
              </a>
              <a 
                class="nav-link" 
                :class="{ 'active': activeTab === 'security' }"
                href="#" 
                @click.prevent="activeTab = 'security'"
              >
                <i class="ti ti-lock mr-2"></i>Security
              </a>
              <a 
                class="nav-link" 
                :class="{ 'active': activeTab === 'preferences' }"
                href="#" 
                @click.prevent="activeTab = 'preferences'"
              >
                <i class="ti ti-settings mr-2"></i>Preferences
              </a>
            </div>
          </template>
        </Card>
      </div>
      
      <div class="lg:col-span-2">
        <Card>
          <template #body>
            <!-- Profile Information Tab -->
            <div v-show="activeTab === 'profile'">
              <h5 class="mb-2">Profile Information</h5>
              <p class="text-muted mb-4">Update your profile information and email address.</p>
              
              <form @submit.prevent="updateProfile">
                <div class="mb-4">
                  <label for="name" class="form-label">Full Name</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    v-model="profileForm.name"
                  >
                </div>
                
                <div class="mb-4">
                  <label for="email" class="form-label">Email Address</label>
                  <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    v-model="profileForm.email"
                  >
                </div>
                
                <div class="mb-4">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input 
                    type="tel" 
                    class="form-control" 
                    id="phone" 
                    v-model="profileForm.phone"
                  >
                </div>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
            </div>
            
            <!-- Security Tab -->
            <div v-show="activeTab === 'security'">
              <h5 class="mb-2">Security Settings</h5>
              <p class="text-muted mb-4">Manage your password and security preferences.</p>
              
              <form @submit.prevent="updatePassword">
                <div class="mb-4">
                  <label for="currentPassword" class="form-label">Current Password</label>
                  <input 
                    type="password" 
                    class="form-control" 
                    id="currentPassword" 
                    v-model="securityForm.currentPassword"
                  >
                </div>
                
                <div class="mb-4">
                  <label for="newPassword" class="form-label">New Password</label>
                  <input 
                    type="password" 
                    class="form-control" 
                    id="newPassword" 
                    v-model="securityForm.newPassword"
                  >
                </div>
                
                <div class="mb-4">
                  <label for="confirmPassword" class="form-label">Confirm New Password</label>
                  <input 
                    type="password" 
                    class="form-control" 
                    id="confirmPassword" 
                    v-model="securityForm.confirmPassword"
                  >
                </div>
                
                <button type="submit" class="btn btn-primary">Update Password</button>
              </form>
            </div>
            
            <!-- Preferences Tab -->
            <div v-show="activeTab === 'preferences'">
              <h5 class="mb-2">Preferences</h5>
              <p class="text-muted mb-4">Customize your dashboard experience.</p>
              
              <form @submit.prevent="updatePreferences">
                <div class="mb-4">
                  <label for="timezone" class="form-label">Timezone</label>
                  <select class="form-control" id="timezone" v-model="preferencesForm.timezone">
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">Eastern Time</option>
                    <option value="America/Chicago">Central Time</option>
                    <option value="America/Denver">Mountain Time</option>
                    <option value="America/Los_Angeles">Pacific Time</option>
                  </select>
                </div>
                
                <div class="mb-4 form-check">
                  <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="emailNotifications" 
                    v-model="preferencesForm.emailNotifications"
                  >
                  <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                </div>
                
                <div class="mb-4 form-check">
                  <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="darkMode" 
                    v-model="preferencesForm.darkMode"
                  >
                  <label class="form-check-label" for="darkMode">Dark Mode</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Preferences</button>
              </form>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const activeTab = ref('profile')
const userRole = ref('Super Admin')

const user = ref({
  email: '',
  profile: {
    fullName: '',
    phone: ''
  },
  timezone: 'UTC',
  emailNotifications: true,
  darkMode: false
})

const profileForm = ref({
  name: '',
  email: '',
  phone: ''
})

const securityForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const preferencesForm = ref({
  timezone: 'UTC',
  emailNotifications: true,
  darkMode: false
})

// Methods
const getInitials = (name) => {
  return name ? name.charAt(0).toUpperCase() : 'U'
}

const updateProfile = () => {
  // In a real implementation, this would make an API call
  user.value.email = profileForm.value.email
  user.value.profile.fullName = profileForm.value.name
  user.value.profile.phone = profileForm.value.phone
  alert('Profile updated successfully')
}

const updatePassword = () => {
  // In a real implementation, this would make an API call
  if (securityForm.value.newPassword !== securityForm.value.confirmPassword) {
    alert('Passwords do not match')
    return
  }
  
  alert('Password updated successfully')
  securityForm.value = {
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  }
}

const updatePreferences = () => {
  // In a real implementation, this would make an API call
  user.value.timezone = preferencesForm.value.timezone
  user.value.emailNotifications = preferencesForm.value.emailNotifications
  user.value.darkMode = preferencesForm.value.darkMode
  alert('Preferences updated successfully')
}

// Mock data for demonstration
onMounted(() => {
  user.value = {
    email: 'admin@example.com',
    profile: {
      fullName: 'John Admin',
      phone: '+1 (555) 123-4567'
    },
    timezone: 'America/New_York',
    emailNotifications: true,
    darkMode: false
  }
  
  profileForm.value = {
    name: user.value.profile.fullName,
    email: user.value.email,
    phone: user.value.profile.phone
  }
  
  preferencesForm.value = {
    timezone: user.value.timezone,
    emailNotifications: user.value.emailNotifications,
    darkMode: user.value.darkMode
  }
})
</script>

<style scoped>
.grid {
  display: grid;
  gap: 1.5rem;
}

.user-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background-color: #e0f2fe;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 600;
  color: #0369a1;
  margin: 0 auto;
}

.text-muted {
  color: #94a3b8;
}

.nav {
  display: flex;
  flex-direction: column;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem 1rem;
  color: #64748b;
  text-decoration: none;
  border-radius: 0.375rem;
  transition: all 0.2s;
  margin-bottom: 0.25rem;
}

.nav-link:hover {
  background: #f1f5f9;
  color: #3b82f6;
}

.nav-link.active {
  background: #3b82f6;
  color: white;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #334155;
}

.form-control {
  display: block;
  width: 100%;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.5;
  color: #1e293b;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  transition: all 0.2s;
}

.form-control:focus {
  color: #1e293b;
  background-color: #fff;
  border-color: #3b82f6;
  outline: 0;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-check {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-check-input {
  width: 1rem;
  height: 1rem;
  margin: 0;
  accent-color: #3b82f6;
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

.mb-2 {
  margin-bottom: 0.5rem;
}

.mb-4 {
  margin-bottom: 1rem;
}

.mr-2 {
  margin-right: 0.5rem;
}
</style>