<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Ops</span> <span class="breadcrumb-sep">/</span> <span>Analytics</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Analytics Dashboard</h1>
      <div class="page-actions">
        <select class="form-select">
          <option>Last 7 days</option>
          <option selected>Last 30 days</option>
          <option>Last 90 days</option>
          <option>Last year</option>
        </select>
      </div>
    </div>
    
    <div class="stats-grid">
      <StatCard 
        icon="👥"
        value="1,248"
        label="Total Users"
        icon-class="stat-icon-primary"
      />
      
      <StatCard 
        icon="📅"
        value="356"
        label="Active Bookings"
        icon-class="stat-icon-info"
      />
      
      <StatCard 
        icon="💼"
        value="42"
        label="Service Providers"
        icon-class="stat-icon-success"
      />
      
      <StatCard 
        icon="💰"
        value="$12,450"
        label="Revenue (30 days)"
        icon-class="stat-icon-warning"
      />
    </div>
    
    <div class="grid grid-2">
      <Card title="Bookings Trend">
        <div class="chart-container">
          <div class="chart-placeholder">
            <div class="chart-title">Bookings Over Time</div>
            <div class="line-chart">
              <svg viewBox="0 0 400 200" class="chart-svg">
                <polyline 
                  fill="none" 
                  stroke="#3b82f6" 
                  stroke-width="2" 
                  points="0,150 50,120 100,130 150,100 200,110 250,80 300,90 350,60 400,70"
                />
                <circle v-for="(point, index) in [0, 50, 100, 150, 200, 250, 300, 350, 400]" 
                        :key="index" 
                        :cx="point" 
                        :cy="150 - index * 10" 
                        r="4" 
                        fill="#3b82f6" />
              </svg>
            </div>
          </div>
        </div>
      </Card>
      
      <Card title="Revenue Distribution">
        <div class="chart-container">
          <div class="chart-placeholder">
            <div class="chart-title">Revenue by Service Category</div>
            <div class="pie-chart">
              <svg viewBox="0 0 200 200" class="chart-svg">
                <circle cx="100" cy="100" r="80" fill="#3b82f6" />
                <path d="M 100 20 A 80 80 0 0 1 180 100 L 100 100" fill="#10b981" />
                <path d="M 180 100 A 80 80 0 0 1 100 180 L 100 100" fill="#f59e0b" />
                <path d="M 100 180 A 80 80 0 0 1 20 100 L 100 100" fill="#ef4444" />
              </svg>
            </div>
          </div>
        </div>
      </Card>
    </div>
    
    <div class="grid grid-2">
      <Card title="Top Services">
        <Table :headers="['Service', 'Bookings', 'Revenue', 'Rating']">
          <tr v-for="i in 5" :key="i">
            <td>Service {{ i }}</td>
            <td>{{ Math.floor(Math.random() * 100) + 50 }}</td>
            <td>${{ (Math.random() * 5000 + 2000).toFixed(2) }}</td>
            <td>
              <div class="rating">
                <span>⭐</span>
                <span>{{ (4.0 + Math.random() * 1.0).toFixed(1) }}</span>
              </div>
            </td>
          </tr>
        </Table>
      </Card>
      
      <Card title="User Activity">
        <Table :headers="['Activity', 'Count', 'Percentage']">
          <tr v-for="i in 6" :key="i">
            <td>
              <span v-if="i === 1">New Registrations</span>
              <span v-else-if="i === 2">Bookings Created</span>
              <span v-else-if="i === 3">Bookings Completed</span>
              <span v-else-if="i === 4">Profile Updates</span>
              <span v-else-if="i === 5">Reviews Submitted</span>
              <span v-else>Support Tickets</span>
            </td>
            <td>{{ Math.floor(Math.random() * 500) + 100 }}</td>
            <td>
              <div class="progress-bar">
                <div class="progress-fill" :style="{ width: `${Math.floor(Math.random() * 100)}%` }"></div>
              </div>
              <span class="percentage">{{ Math.floor(Math.random() * 100) }}%</span>
            </td>
          </tr>
        </Table>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'

// Helper functions
function getRandomInitial() {
  const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  return letters.charAt(Math.floor(Math.random() * letters.length))
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

.form-select {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
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

.chart-container {
  padding: 1rem 0;
}

.chart-placeholder {
  text-align: center;
}

.chart-title {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 1rem;
}

.line-chart,
.pie-chart {
  display: flex;
  justify-content: center;
}

.chart-svg {
  width: 100%;
  max-width: 400px;
  height: 200px;
}

.rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #f59e0b;
  font-weight: 500;
}

.progress-bar {
  width: 80px;
  height: 8px;
  background-color: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
  margin-right: 0.5rem;
  display: inline-block;
}

.progress-fill {
  height: 100%;
  background-color: #3b82f6;
  border-radius: 4px;
}

.percentage {
  font-size: 0.875rem;
  color: #64748b;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>