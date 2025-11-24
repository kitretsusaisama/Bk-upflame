<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Reviews</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Customer Reviews</h1>
    </div>
    
    <div class="stats-grid">
      <Card title="Overall Rating">
        <div class="rating-summary">
          <div class="rating-value">4.8</div>
          <div class="rating-stars">
            <span v-for="i in 5" :key="i" class="star" :class="{ 'filled': i <= 5 }">⭐</span>
          </div>
          <div class="rating-count">Based on 142 reviews</div>
        </div>
      </Card>
      
      <Card title="Rating Distribution">
        <div class="rating-distribution">
          <div v-for="i in 5" :key="i" class="distribution-item">
            <div class="stars">
              <span v-for="j in 5" :key="j" class="star" :class="{ 'filled': j > 5 - i }">⭐</span>
            </div>
            <div class="bar-container">
              <div class="bar" :style="{ width: `${(6 - i) * 20}%` }"></div>
            </div>
            <div class="count">{{ Math.floor(Math.random() * 40) + 10 }}</div>
          </div>
        </div>
      </Card>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Recent Reviews</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Ratings</option>
            <option>5 Stars</option>
            <option>4 Stars</option>
            <option>3 Stars</option>
            <option>2 Stars</option>
            <option>1 Star</option>
          </select>
          <input type="text" placeholder="Search reviews..." class="form-input">
        </div>
      </template>
      
      <div class="reviews-list">
        <div v-for="i in 10" :key="i" class="review-item">
          <div class="review-header">
            <div class="reviewer">
              <div class="reviewer-avatar">{{ getRandomInitial() }}</div>
              <div class="reviewer-info">
                <div class="reviewer-name">Customer {{ i }}</div>
                <div class="review-date">{{ formatDate(subDays(new Date(), i)) }}</div>
              </div>
            </div>
            <div class="review-rating">
              <span v-for="j in 5" :key="j" class="star" :class="{ 'filled': j <= 6 - i }">⭐</span>
            </div>
          </div>
          <div class="review-content">
            <p>This was an excellent service! The provider was professional, punctual, and did a great job. I would definitely recommend them to others.</p>
          </div>
          <div class="review-actions">
            <button class="btn btn-outline btn-sm">Reply</button>
            <button class="btn btn-outline btn-sm">Report</button>
          </div>
        </div>
      </div>
      
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.rating-summary {
  text-align: center;
  padding: 1rem 0;
}

.rating-value {
  font-size: 3rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.rating-stars {
  font-size: 1.5rem;
  color: #e2e8f0;
  margin-bottom: 0.5rem;
}

.rating-stars .filled {
  color: #f59e0b;
}

.rating-count {
  color: #64748b;
  font-size: 0.875rem;
}

.rating-distribution {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1rem 0;
}

.distribution-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stars {
  color: #e2e8f0;
  font-size: 0.875rem;
  min-width: 70px;
}

.stars .filled {
  color: #f59e0b;
}

.bar-container {
  flex: 1;
  height: 8px;
  background-color: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
}

.bar {
  height: 100%;
  background-color: #f59e0b;
  border-radius: 4px;
}

.count {
  font-size: 0.875rem;
  color: #64748b;
  min-width: 30px;
  text-align: right;
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

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1rem 0;
}

.review-item {
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background-color: #f8fafc;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.reviewer {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.reviewer-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 9999px;
  background-color: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 1.25rem;
}

.reviewer-info {
  min-width: 0;
}

.reviewer-name {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.review-date {
  font-size: 0.875rem;
  color: #64748b;
}

.review-rating {
  font-size: 1.25rem;
  color: #e2e8f0;
}

.review-rating .filled {
  color: #f59e0b;
}

.review-content {
  margin-bottom: 1rem;
  color: #334155;
  line-height: 1.6;
}

.review-actions {
  display: flex;
  gap: 0.5rem;
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

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .card-actions {
    flex-direction: column;
  }
  
  .review-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}
</style>