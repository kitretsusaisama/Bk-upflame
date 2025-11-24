<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Profile</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Provider Profile</h1>
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
            <label class="form-label">Specialization</label>
            <input type="text" class="form-input" v-model="profileForm.specialization" placeholder="Enter your specialization">
          </div>
          
          <div class="form-group">
            <label class="form-label">Years of Experience</label>
            <input type="number" class="form-input" v-model="profileForm.experience" min="0" max="50">
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
        
        <Card title="Certifications">
          <div class="certifications-list">
            <div v-for="i in 3" :key="i" class="certification-item">
              <div class="certification-name">Certification {{ i }}</div>
              <div class="certification-issuer">Issuer {{ i }}</div>
              <div class="certification-date">Issued: {{ formatDate(subDays(new Date(), i * 30)) }}</div>
            </div>
            <button class="btn btn-outline btn-sm btn-block" @click="showAddCertificationModal = true">
              Add Certification
            </button>
          </div>
        </Card>
        
        <Card title="Provider Statistics">
          <div class="stats-list">
            <div class="stat-item">
              <div class="stat-label">Total Bookings</div>
              <div class="stat-value">248</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Completed</div>
              <div class="stat-value">234</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Average Rating</div>
              <div class="stat-value">4.8</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Total Earnings</div>
              <div class="stat-value">$12,450</div>
            </div>
          </div>
        </Card>
      </div>
    </div>
    
    <Card title="Business Information">
      <form @submit.prevent="updateBusinessInfo">
        <div class="grid grid-2">
          <div class="form-group">
            <label class="form-label">Business Name</label>
            <input type="text" class="form-input" v-model="businessForm.name" placeholder="Enter business name">
          </div>
          
          <div class="form-group">
            <label class="form-label">Business Address</label>
            <input type="text" class="form-input" v-model="businessForm.address" placeholder="Enter business address">
          </div>
          
          <div class="form-group">
            <label class="form-label">City</label>
            <input type="text" class="form-input" v-model="businessForm.city" placeholder="Enter city">
          </div>
          
          <div class="form-group">
            <label class="form-label">State/Province</label>
            <input type="text" class="form-input" v-model="businessForm.state" placeholder="Enter state/province">
          </div>
          
          <div class="form-group">
            <label class="form-label">Postal Code</label>
            <input type="text" class="form-input" v-model="businessForm.postalCode" placeholder="Enter postal code">
          </div>
          
          <div class="form-group">
            <label class="form-label">Country</label>
            <select class="form-select" v-model="businessForm.country">
              <option value="">Select a country</option>
              <option value="us">United States</option>
              <option value="ca">Canada</option>
              <option value="uk">United Kingdom</option>
            </select>
          </div>
        </div>
        
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Update Business Info</button>
        </div>
      </form>
    </Card>
    
    <!-- Add Certification Modal -->
    <div v-if="showAddCertificationModal" class="modal-overlay" @click="showAddCertificationModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add Certification</h3>
          <button class="modal-close" @click="showAddCertificationModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="addCertification">
            <div class="form-group">
              <label class="form-label">Certification Name</label>
              <input type="text" class="form-input" v-model="certificationForm.name" placeholder="Enter certification name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Issuing Organization</label>
              <input type="text" class="form-input" v-model="certificationForm.issuer" placeholder="Enter issuing organization">
            </div>
            
            <div class="form-group">
              <label class="form-label">Issue Date</label>
              <input type="date" class="form-input" v-model="certificationForm.issueDate">
            </div>
            
            <div class="form-group">
              <label class="form-label">Expiration Date (optional)</label>
              <input type="date" class="form-input" v-model="certificationForm.expiryDate">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showAddCertificationModal = false">Cancel</button>
          <button class="btn btn-primary" @click="addCertification">Add Certification</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Modal state
const showAddCertificationModal = ref(false)

// Form data
const profileForm = reactive({
  name: 'John Provider',
  email: 'john.provider@example.com',
  phone: '+1 (555) 123-4567',
  specialization: 'Plumbing',
  experience: 8,
  bio: 'Experienced provider with 8 years in the industry. Specializing in residential and commercial plumbing services.'
})

const businessForm = reactive({
  name: 'John\'s Plumbing Services',
  address: '123 Main Street',
  city: 'Anytown',
  state: 'ST',
  postalCode: '12345',
  country: 'us'
})

const certificationForm = reactive({
  name: '',
  issuer: '',
  issueDate: '',
  expiryDate: ''
})

// Helper function to get user initials
function getUserInitials() {
  if (profileForm.name) {
    return profileForm.name.charAt(0).toUpperCase()
  }
  return 'P'
}

// Helper functions
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

// Form submission handlers
function updateProfile() {
  console.log('Updating profile:', profileForm)
  alert('Profile updated successfully!')
}

function updateBusinessInfo() {
  console.log('Updating business info:', businessForm)
  alert('Business information updated successfully!')
}

function addCertification() {
  console.log('Adding certification:', certificationForm)
  alert('Certification added successfully!')
  showAddCertificationModal.value = false
  
  // Reset form
  certificationForm.name = ''
  certificationForm.issuer = ''
  certificationForm.issueDate = ''
  certificationForm.expiryDate = ''
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
.form-select,
.form-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus,
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

.certifications-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.certification-item {
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  background-color: #f8fafc;
}

.certification-name {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.certification-issuer {
  font-size: 0.875rem;
  color: #64748b;
  margin-bottom: 0.25rem;
}

.certification-date {
  font-size: 0.875rem;
  color: #94a3b8;
}

.btn-block {
  width: 100%;
  margin-top: 0.5rem;
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
  max-width: 500px;
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
}
</style>