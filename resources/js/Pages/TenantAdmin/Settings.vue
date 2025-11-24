<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Settings</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Tenant Settings</h1>
    </div>
    
    <div class="settings-grid">
      <Card title="General Settings">
        <form @submit.prevent="updateGeneralSettings">
          <div class="form-group">
            <label class="form-label">Tenant Name</label>
            <input type="text" class="form-input" v-model="settingsForm.tenantName" placeholder="Enter tenant name">
          </div>
          
          <div class="form-group">
            <label class="form-label">Contact Email</label>
            <input type="email" class="form-input" v-model="settingsForm.contactEmail" placeholder="Enter contact email">
          </div>
          
          <div class="form-group">
            <label class="form-label">Timezone</label>
            <select class="form-select" v-model="settingsForm.timezone">
              <option value="UTC">UTC</option>
              <option value="America/New_York">Eastern Time</option>
              <option value="America/Chicago">Central Time</option>
              <option value="America/Denver">Mountain Time</option>
              <option value="America/Los_Angeles">Pacific Time</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label">Default Currency</label>
            <select class="form-select" v-model="settingsForm.currency">
              <option value="USD">USD - US Dollar</option>
              <option value="EUR">EUR - Euro</option>
              <option value="GBP">GBP - British Pound</option>
            </select>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </Card>
      
      <Card title="Booking Settings">
        <form @submit.prevent="updateBookingSettings">
          <div class="form-group">
            <label class="form-label">Booking Window (days)</label>
            <input type="number" class="form-input" v-model="bookingForm.window" min="1" max="365">
          </div>
          
          <div class="form-group">
            <label class="form-label">Cancellation Policy</label>
            <select class="form-select" v-model="bookingForm.cancellation">
              <option value="24">24 hours before</option>
              <option value="48">48 hours before</option>
              <option value="72">72 hours before</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label">Auto-Confirm Bookings</label>
            <div class="toggle-switch">
              <input type="checkbox" id="autoConfirm" v-model="bookingForm.autoConfirm" class="toggle-input">
              <label for="autoConfirm" class="toggle-label"></label>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Notifications</label>
            <div class="notification-options">
              <label class="notification-option">
                <input type="checkbox" v-model="bookingForm.notifyCustomer" class="notification-checkbox">
                <span>Notify customers of booking confirmation</span>
              </label>
              <label class="notification-option">
                <input type="checkbox" v-model="bookingForm.notifyProvider" class="notification-checkbox">
                <span>Notify providers of new bookings</span>
              </label>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </Card>
      
      <Card title="Appearance">
        <form @submit.prevent="updateAppearanceSettings">
          <div class="form-group">
            <label class="form-label">Logo</label>
            <div class="logo-upload">
              <div class="logo-preview">
                <div class="logo-placeholder">No logo uploaded</div>
              </div>
              <button type="button" class="btn btn-outline btn-sm">Upload Logo</button>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Primary Color</label>
            <div class="color-picker">
              <input type="color" v-model="appearanceForm.primaryColor" class="color-input">
              <span class="color-value">{{ appearanceForm.primaryColor }}</span>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Theme</label>
            <div class="theme-options">
              <label class="theme-option">
                <input type="radio" v-model="appearanceForm.theme" value="light">
                <span class="theme-label">Light</span>
              </label>
              <label class="theme-option">
                <input type="radio" v-model="appearanceForm.theme" value="dark">
                <span class="theme-label">Dark</span>
              </label>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </Card>
      
      <Card title="Security">
        <form @submit.prevent="updateSecuritySettings">
          <div class="form-group">
            <label class="form-label">Session Timeout (minutes)</label>
            <input type="number" class="form-input" v-model="securityForm.sessionTimeout" min="5" max="1440">
          </div>
          
          <div class="form-group">
            <label class="form-label">Two-Factor Authentication</label>
            <div class="toggle-switch">
              <input type="checkbox" id="twoFactor" v-model="securityForm.twoFactor" class="toggle-input">
              <label for="twoFactor" class="toggle-label"></label>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Password Requirements</label>
            <div class="password-requirements">
              <label class="requirement-option">
                <input type="checkbox" v-model="securityForm.requireUppercase" class="requirement-checkbox">
                <span>Require uppercase letters</span>
              </label>
              <label class="requirement-option">
                <input type="checkbox" v-model="securityForm.requireNumbers" class="requirement-checkbox">
                <span>Require numbers</span>
              </label>
              <label class="requirement-option">
                <input type="checkbox" v-model="securityForm.requireSpecial" class="requirement-checkbox">
                <span>Require special characters</span>
              </label>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Form data
const settingsForm = reactive({
  tenantName: 'Acme Corporation',
  contactEmail: 'admin@acme.com',
  timezone: 'America/New_York',
  currency: 'USD'
})

const bookingForm = reactive({
  window: 30,
  cancellation: '24',
  autoConfirm: true,
  notifyCustomer: true,
  notifyProvider: true
})

const appearanceForm = reactive({
  primaryColor: '#3b82f6',
  theme: 'light'
})

const securityForm = reactive({
  sessionTimeout: 30,
  twoFactor: false,
  requireUppercase: true,
  requireNumbers: true,
  requireSpecial: false
})

// Form submission handlers
function updateGeneralSettings() {
  console.log('Updating general settings:', settingsForm)
  alert('General settings updated successfully!')
}

function updateBookingSettings() {
  console.log('Updating booking settings:', bookingForm)
  alert('Booking settings updated successfully!')
}

function updateAppearanceSettings() {
  console.log('Updating appearance settings:', appearanceForm)
  alert('Appearance settings updated successfully!')
}

function updateSecuritySettings() {
  console.log('Updating security settings:', securityForm)
  alert('Security settings updated successfully!')
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

.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.form-input,
.form-select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
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

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.toggle-input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-label {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #cbd5e1;
  transition: .4s;
  border-radius: 24px;
}

.toggle-label:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

.toggle-input:checked + .toggle-label {
  background-color: #3b82f6;
}

.toggle-input:checked + .toggle-label:before {
  transform: translateX(26px);
}

.notification-options,
.password-requirements {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.notification-option,
.requirement-option {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.notification-checkbox,
.requirement-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 0.25rem;
  border: 1px solid #cbd5e1;
  cursor: pointer;
}

.theme-options {
  display: flex;
  gap: 1rem;
}

.theme-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.theme-label {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  background-color: #f1f5f9;
  font-size: 0.875rem;
  cursor: pointer;
}

.logo-upload {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-preview {
  width: 80px;
  height: 80px;
  border: 1px dashed #cbd5e1;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-placeholder {
  color: #94a3b8;
  font-size: 0.75rem;
  text-align: center;
}

.color-picker {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.color-input {
  width: 50px;
  height: 40px;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
}

.color-value {
  font-family: monospace;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .settings-grid {
    grid-template-columns: 1fr;
  }
  
  .theme-options {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>