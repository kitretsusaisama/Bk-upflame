<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>System</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">System Analytics</h1>
      <p class="page-subtitle">View system-wide analytics and metrics.</p>
    </div>
    
    <Card class="card-spacing">
      <template #header>
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a 
              class="nav-link" 
              :class="{ 'active': activeTab === 'usage' }"
              href="#" 
              @click.prevent="activeTab = 'usage'"
            >
              Usage
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              :class="{ 'active': activeTab === 'performance' }"
              href="#" 
              @click.prevent="activeTab = 'performance'"
            >
              Performance
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              :class="{ 'active': activeTab === 'revenue' }"
              href="#" 
              @click.prevent="activeTab = 'revenue'"
            >
              Revenue
            </a>
          </li>
        </ul>
      </template>
      
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h5>Tenant Growth</h5>
            <div class="chart-container">
              <div class="chart-placeholder">
                <div class="chart-grid">
                  <div class="chart-bar" style="height: 60%"></div>
                  <div class="chart-bar" style="height: 75%"></div>
                  <div class="chart-bar" style="height: 45%"></div>
                  <div class="chart-bar" style="height: 85%"></div>
                  <div class="chart-bar" style="height: 65%"></div>
                  <div class="chart-bar" style="height: 90%"></div>
                  <div class="chart-bar" style="height: 70%"></div>
                </div>
                <div class="chart-labels">
                  <span>Jan</span>
                  <span>Feb</span>
                  <span>Mar</span>
                  <span>Apr</span>
                  <span>May</span>
                  <span>Jun</span>
                  <span>Jul</span>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <h5>User Activity</h5>
            <div class="chart-container">
              <div class="chart-placeholder">
                <div class="chart-grid">
                  <div class="chart-bar" style="height: 40%"></div>
                  <div class="chart-bar" style="height: 55%"></div>
                  <div class="chart-bar" style="height: 35%"></div>
                  <div class="chart-bar" style="height: 70%"></div>
                  <div class="chart-bar" style="height: 60%"></div>
                  <div class="chart-bar" style="height: 80%"></div>
                  <div class="chart-bar" style="height: 65%"></div>
                </div>
                <div class="chart-labels">
                  <span>Mon</span>
                  <span>Tue</span>
                  <span>Wed</span>
                  <span>Thu</span>
                  <span>Fri</span>
                  <span>Sat</span>
                  <span>Sun</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-6">
          <h5>Subscription Distribution</h5>
          <div class="chart-container">
            <div class="pie-chart-placeholder">
              <div class="pie-chart">
                <div class="pie-slice" style="background-color: #3b82f6; transform: rotate(0deg);"></div>
                <div class="pie-slice" style="background-color: #10b981; transform: rotate(90deg);"></div>
                <div class="pie-slice" style="background-color: #f59e0b; transform: rotate(180deg);"></div>
                <div class="pie-slice" style="background-color: #ef4444; transform: rotate(270deg);"></div>
              </div>
              <div class="pie-legend">
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #3b82f6;"></div>
                  <span>Enterprise (35%)</span>
                </div>
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #10b981;"></div>
                  <span>Premium (25%)</span>
                </div>
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #f59e0b;"></div>
                  <span>Basic (25%)</span>
                </div>
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #ef4444;"></div>
                  <span>Free (15%)</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </Card>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <Card title="Recent Activity" class="card-spacing">
        <template #body>
          <ul class="list-group">
            <li class="list-group-item" v-for="(activity, index) in recentActivities" :key="index">
              <div class="d-flex justify-content-between">
                <span>{{ activity.description }}</span>
                <small class="text-muted">{{ activity.time }}</small>
              </div>
            </li>
          </ul>
        </template>
      </Card>
      
      <Card title="System Metrics" class="card-spacing">
        <template #body>
          <div class="list-group">
            <div class="list-group-item d-flex justify-content-between align-items-center">
              API Requests (24h)
              <Badge type="primary">12,458</Badge>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Average Response Time
              <Badge type="success">142ms</Badge>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Error Rate
              <Badge type="danger">0.2%</Badge>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Active Users (24h)
              <Badge type="info">1,240</Badge>
            </div>
          </div>
        </template>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Badge from '@/Components/UI/Badge.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const activeTab = ref('usage')
const recentActivities = ref([])

// Mock data for demonstration
onMounted(() => {
  recentActivities.value = [
    {
      description: 'New tenant registered: Acme Corp',
      time: '2 hours ago'
    },
    {
      description: 'Subscription upgraded: Globex Inc',
      time: '5 hours ago'
    },
    {
      description: 'Provider added: Dr. Jane Smith',
      time: '1 day ago'
    }
  ]
})
</script>

<style scoped>
.page-header {
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: #64748b;
  margin: 0;
}

.card-spacing {
  margin-bottom: 1.5rem;
}

.nav {
  display: flex;
  flex-wrap: wrap;
  padding-left: 0;
  margin-bottom: 0;
  list-style: none;
}

.nav-tabs {
  border-bottom: 1px solid #e2e8f0;
}

.nav-item {
  margin-bottom: -1px;
}

.nav-link {
  display: block;
  padding: 0.5rem 1rem;
  border: 1px solid transparent;
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
  color: #64748b;
  text-decoration: none;
  transition: all 0.2s;
}

.nav-link:hover {
  border-color: #f1f5f9 #f1f5f9 #e2e8f0;
  color: #334155;
}

.nav-link.active {
  color: #3b82f6;
  background-color: #ffffff;
  border-color: #e2e8f0 #e2e8f0 #ffffff;
  font-weight: 500;
}

.grid {
  display: grid;
  gap: 1.5rem;
}

h5 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 1rem;
}

.chart-container {
  padding: 1rem;
  background-color: #f8fafc;
  border-radius: 0.5rem;
}

.chart-placeholder {
  height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.chart-grid {
  display: flex;
  align-items: flex-end;
  height: 160px;
  gap: 1rem;
  border-bottom: 1px solid #cbd5e1;
  padding-bottom: 1rem;
}

.chart-bar {
  flex: 1;
  background-color: #3b82f6;
  border-radius: 0.25rem 0.25rem 0 0;
  min-width: 30px;
}

.chart-labels {
  display: flex;
  justify-content: space-between;
  margin-top: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
}

.pie-chart-placeholder {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.pie-chart {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  overflow: hidden;
}

.pie-slice {
  position: absolute;
  width: 100%;
  height: 100%;
  clip-path: polygon(50% 50%, 50% 0%, 100% 0%, 100% 100%, 50% 100%);
}

.pie-legend {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.legend-color {
  width: 1rem;
  height: 1rem;
  border-radius: 0.25rem;
}

.list-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.list-group-item {
  padding: 0.75rem 1rem;
  background-color: #f8fafc;
  border-radius: 0.375rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.text-muted {
  color: #94a3b8;
}

.d-flex {
  display: flex;
}

.justify-content-between {
  justify-content: space-between;
}

.align-items-center {
  align-items: center;
}

.mt-6 {
  margin-top: 1.5rem;
}
</style>