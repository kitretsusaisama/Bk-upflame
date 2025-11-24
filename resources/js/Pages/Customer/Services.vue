<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Services</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Available Services</h1>
      <div class="page-actions">
        <div class="search-box">
          <input type="text" placeholder="Search services..." class="form-input">
          <span class="search-icon">🔍</span>
        </div>
        <select class="form-select">
          <option>All Categories</option>
          <option>Healthcare</option>
          <option>Beauty & Wellness</option>
          <option>Home Services</option>
          <option>Auto Services</option>
        </select>
      </div>
    </div>
    
    <div class="services-grid">
      <div v-for="i in 12" :key="i" class="service-card">
        <div class="service-image">
          <div class="service-icon">🔧</div>
        </div>
        <div class="service-content">
          <h3 class="service-title">Service {{ i }}</h3>
          <p class="service-description">
            Professional service description that explains what this service offers and its benefits.
          </p>
          <div class="service-meta">
            <div class="service-rating">
              <span>⭐</span>
              <span>4.{{ Math.floor(Math.random() * 9) }}</span>
              <span>({{ Math.floor(Math.random() * 100) + 10 }} reviews)</span>
            </div>
            <div class="service-price">${{ (Math.random() * 100 + 50).toFixed(2) }}</div>
          </div>
          <button class="btn btn-primary btn-block">
            Book Now
          </button>
        </div>
      </div>
    </div>
    
    <div class="pagination">
      <button class="btn btn-outline">Previous</button>
      <div class="pagination-pages">
        <button v-for="i in 5" :key="i" class="btn" :class="i === 1 ? 'btn-primary' : 'btn-outline'">
          {{ i }}
        </button>
      </div>
      <button class="btn btn-outline">Next</button>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
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
  gap: 1rem;
  align-items: center;
}

.search-box {
  position: relative;
}

.form-input {
  padding: 0.5rem 2.5rem 0.5rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  width: 250px;
}

.search-icon {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
}

.form-select {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
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

.service-image {
  height: 150px;
  background-color: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
}

.service-icon {
  font-size: 3rem;
  color: #94a3b8;
}

.service-content {
  padding: 1.5rem;
}

.service-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.service-description {
  color: #64748b;
  margin: 0 0 1rem 0;
  line-height: 1.5;
}

.service-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.service-rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #f59e0b;
  font-weight: 500;
}

.service-rating span:first-child {
  font-size: 1.25rem;
}

.service-rating span:last-child {
  color: #94a3b8;
  font-weight: normal;
}

.service-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.btn-block {
  width: 100%;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 0;
}

.pagination-pages {
  display: flex;
  gap: 0.5rem;
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
  }
  
  .page-actions {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-box .form-input {
    width: 100%;
  }
  
  .services-grid {
    grid-template-columns: 1fr;
  }
}
</style>