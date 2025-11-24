<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Services</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">My Services</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showCreateServiceModal = true">
          <span>➕</span> Add Service
        </button>
      </div>
    </div>
    
    <div class="services-grid">
      <div v-for="i in 8" :key="i" class="service-card">
        <div class="service-header">
          <div class="service-icon">🔧</div>
          <div class="service-actions">
            <button class="btn-icon" title="Edit">✏️</button>
            <button class="btn-icon" title="Delete">🗑️</button>
          </div>
        </div>
        <div class="service-content">
          <h3 class="service-title">Service {{ i }}</h3>
          <p class="service-description">
            Professional service description that explains what this service offers and its benefits to customers.
          </p>
          <div class="service-meta">
            <div class="service-duration">
              <span>⏱️</span>
              <span>{{ Math.floor(Math.random() * 3) + 1 }} hour{{ Math.floor(Math.random() * 3) + 1 > 1 ? 's' : '' }}</span>
            </div>
            <div class="service-price">${{ (Math.random() * 100 + 50).toFixed(2) }}</div>
          </div>
        </div>
        <div class="service-footer">
          <Badge v-if="i % 3 === 0" type="success">Active</Badge>
          <Badge v-else-if="i % 3 === 1" type="warning">Draft</Badge>
          <Badge v-else type="error">Inactive</Badge>
          <div class="service-rating">
            <span>⭐</span>
            <span>{{ (4.0 + Math.random() * 1.0).toFixed(1) }}</span>
            <span>({{ Math.floor(Math.random() * 50) + 10 }})</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Create Service Modal -->
    <div v-if="showCreateServiceModal" class="modal-overlay" @click="showCreateServiceModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add New Service</h3>
          <button class="modal-close" @click="showCreateServiceModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createService">
            <div class="form-group">
              <label class="form-label">Service Name</label>
              <input type="text" class="form-input" v-model="serviceForm.name" placeholder="Enter service name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea class="form-textarea" v-model="serviceForm.description" rows="3" placeholder="Enter service description"></textarea>
            </div>
            
            <div class="form-group">
              <label class="form-label">Category</label>
              <select class="form-select" v-model="serviceForm.category">
                <option value="">Select a category</option>
                <option value="healthcare">Healthcare</option>
                <option value="beauty">Beauty & Wellness</option>
                <option value="home">Home Services</option>
                <option value="auto">Auto Services</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Duration (minutes)</label>
              <input type="number" class="form-input" v-model="serviceForm.duration" min="15" max="240" step="15">
            </div>
            
            <div class="form-group">
              <label class="form-label">Price</label>
              <div class="price-input">
                <span class="currency">$</span>
                <input type="number" class="form-input" v-model="serviceForm.price" min="0" step="0.01">
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">Status</label>
              <select class="form-select" v-model="serviceForm.status">
                <option value="active">Active</option>
                <option value="draft">Draft</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showCreateServiceModal = false">Cancel</button>
          <button class="btn btn-primary" @click="createService">Create Service</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Badge from '@/Components/UI/Badge.vue'

// Modal state
const showCreateServiceModal = ref(false)

// Form data
const serviceForm = reactive({
  name: '',
  description: '',
  category: '',
  duration: 60,
  price: 0,
  status: 'active'
})

// Form submission handler
function createService() {
  console.log('Creating service:', serviceForm)
  // In a real app, you would send this data to your backend
  alert('Service created successfully!')
  
  // Reset form and close modal
  serviceForm.name = ''
  serviceForm.description = ''
  serviceForm.category = ''
  serviceForm.duration = 60
  serviceForm.price = 0
  serviceForm.status = 'active'
  showCreateServiceModal.value = false
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

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.service-card {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease;
}

.service-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.service-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.service-icon {
  font-size: 1.5rem;
  color: #94a3b8;
}

.service-actions {
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

.service-content {
  padding: 1.5rem;
}

.service-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 0.75rem 0;
}

.service-description {
  color: #64748b;
  margin: 0 0 1.5rem 0;
  line-height: 1.5;
}

.service-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.service-duration {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
}

.service-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.service-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background-color: #f8fafc;
}

.service-rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #f59e0b;
  font-weight: 500;
  font-size: 0.875rem;
}

.service-rating span:last-child {
  color: #94a3b8;
  font-weight: normal;
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

.price-input {
  display: flex;
  align-items: center;
}

.currency {
  padding: 0.75rem;
  background-color: #f1f5f9;
  border: 1px solid #cbd5e1;
  border-right: none;
  border-radius: 0.375rem 0 0 0.375rem;
  font-weight: 500;
}

.form-input[type="number"] {
  border-radius: 0 0.375rem 0.375rem 0;
  border-left: none;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
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
  .services-grid {
    grid-template-columns: 1fr;
  }
}
</style>