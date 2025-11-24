<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Super Admin</span> <span class="breadcrumb-sep">/</span> <span>Reports</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">System Reports</h1>
      <p class="page-subtitle">Generate and view system-wide reports.</p>
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
              Usage Reports
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              :class="{ 'active': activeTab === 'financial' }"
              href="#" 
              @click.prevent="activeTab = 'financial'"
            >
              Financial Reports
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              :class="{ 'active': activeTab === 'audit' }"
              href="#" 
              @click.prevent="activeTab = 'audit'"
            >
              Audit Reports
            </a>
          </li>
        </ul>
      </template>
      
      <template #body>
        <div class="report-filters mb-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="form-label">Date Range</label>
              <select class="form-control" v-model="reportFilters.dateRange">
                <option value="last7">Last 7 Days</option>
                <option value="last30" selected>Last 30 Days</option>
                <option value="last90">Last 90 Days</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            
            <div>
              <label class="form-label">Tenant</label>
              <select class="form-control" v-model="reportFilters.tenant">
                <option value="">All Tenants</option>
                <option value="1">Acme Corp</option>
                <option value="2">Globex Inc</option>
                <option value="3">Wayne Enterprises</option>
              </select>
            </div>
            
            <div>
              <label class="form-label">Format</label>
              <select class="form-control" v-model="reportFilters.format">
                <option value="pdf">PDF</option>
                <option value="csv">CSV</option>
                <option value="xlsx">Excel</option>
              </select>
            </div>
            
            <div class="flex items-end">
              <button class="btn btn-primary w-full" @click="generateReport">
                Generate Report
              </button>
            </div>
          </div>
        </div>
        
        <div v-if="activeTab === 'usage'">
          <h5>Usage Statistics</h5>
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
          
          <div class="mt-6">
            <h5>Resource Usage</h5>
            <Table :headers="['Resource', 'Usage', 'Limit', 'Status']">
              <tr>
                <td>API Requests</td>
                <td>12,458</td>
                <td>50,000</td>
                <td><Badge type="success">Normal</Badge></td>
              </tr>
              <tr>
                <td>Storage</td>
                <td>45.2 GB</td>
                <td>100 GB</td>
                <td><Badge type="warning">Warning</Badge></td>
              </tr>
              <tr>
                <td>Bandwidth</td>
                <td>125 GB</td>
                <td>500 GB</td>
                <td><Badge type="success">Normal</Badge></td>
              </tr>
              <tr>
                <td>Active Users</td>
                <td>1,240</td>
                <td>5,000</td>
                <td><Badge type="success">Normal</Badge></td>
              </tr>
            </Table>
          </div>
        </div>
        
        <div v-if="activeTab === 'financial'">
          <h5>Financial Overview</h5>
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
          
          <div class="mt-6">
            <h5>Revenue Breakdown</h5>
            <Table :headers="['Subscription', 'Tenants', 'Revenue', 'Growth']">
              <tr>
                <td>Enterprise</td>
                <td>12</td>
                <td>$12,000</td>
                <td><span class="text-success">+12%</span></td>
              </tr>
              <tr>
                <td>Premium</td>
                <td>24</td>
                <td>$4,800</td>
                <td><span class="text-success">+8%</span></td>
              </tr>
              <tr>
                <td>Basic</td>
                <td>45</td>
                <td>$1,350</td>
                <td><span class="text-warning">+3%</span></td>
              </tr>
              <tr>
                <td>Free</td>
                <td>120</td>
                <td>$0</td>
                <td><span class="text-muted">0%</span></td>
              </tr>
            </Table>
          </div>
        </div>
        
        <div v-if="activeTab === 'audit'">
          <h5>Audit Logs Summary</h5>
          <Table :headers="['Action', 'Count', 'Last Occurrence', 'Status']">
            <tr>
              <td>User Login</td>
              <td>3,421</td>
              <td>2 minutes ago</td>
              <td><Badge type="success">Normal</Badge></td>
            </tr>
            <tr>
              <td>Data Export</td>
              <td>156</td>
              <td>1 hour ago</td>
              <td><Badge type="success">Normal</Badge></td>
            </tr>
            <tr>
              <td>Permission Change</td>
              <td>24</td>
              <td>3 hours ago</td>
              <td><Badge type="warning">Review</Badge></td>
            </tr>
            <tr>
              <td>Failed Login</td>
              <td>8</td>
              <td>5 hours ago</td>
              <td><Badge type="danger">Alert</Badge></td>
            </tr>
          </Table>
          
          <div class="mt-4">
            <button class="btn btn-outline-primary">View Detailed Logs</button>
          </div>
        </div>
      </template>
    </Card>
    
    <Card title="Saved Reports" class="card-spacing">
      <template #body>
        <Table :headers="['Report Name', 'Generated', 'Format', 'Status', 'Actions']">
          <tr v-for="report in savedReports" :key="report.id">
            <td>{{ report.name }}</td>
            <td>{{ formatDate(report.generated) }}</td>
            <td>{{ report.format.toUpperCase() }}</td>
            <td><Badge :type="report.status === 'completed' ? 'success' : 'warning'">{{ capitalize(report.status) }}</Badge></td>
            <td>
              <button class="btn btn-sm btn-info mr-1">View</button>
              <button class="btn btn-sm btn-primary mr-1">Download</button>
              <button class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
          
          <tr v-if="savedReports.length === 0">
            <td colspan="5" class="text-center">No saved reports found</td>
          </tr>
        </Table>
      </template>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Props from Inertia
const { props } = usePage()
const auth = props.value.auth || {}
const menu = props.value.menu || []
const flash = props.value.flash || {}

// State
const activeTab = ref('usage')
const savedReports = ref([])

const reportFilters = ref({
  dateRange: 'last30',
  tenant: '',
  format: 'pdf'
})

// Methods
const generateReport = () => {
  // In a real implementation, this would make an API call
  alert(`Generating ${activeTab.value} report in ${reportFilters.value.format} format`)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const capitalize = (str) => {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

// Mock data for demonstration
onMounted(() => {
  savedReports.value = [
    {
      id: 1,
      name: 'Monthly Usage Report - July 2023',
      generated: '2023-07-31T10:30:00Z',
      format: 'pdf',
      status: 'completed'
    },
    {
      id: 2,
      name: 'Financial Summary - Q2 2023',
      generated: '2023-06-30T15:45:00Z',
      format: 'xlsx',
      status: 'completed'
    },
    {
      id: 3,
      name: 'Audit Log Summary - Last 30 Days',
      generated: '2023-07-15T09:20:00Z',
      format: 'csv',
      status: 'processing'
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
  gap: 1rem;
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
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
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

.btn-outline-primary {
  border: 1px solid #3b82f6;
  color: #3b82f6;
  background-color: transparent;
}

.btn-outline-primary:hover {
  background-color: #3b82f6;
  color: white;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-info {
  background-color: #0ea5e9;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-info:hover {
  background-color: #0284c7;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn-danger:hover {
  background-color: #dc2626;
}

.mr-1 {
  margin-right: 0.25rem;
}

.mb-4 {
  margin-bottom: 1rem;
}

.mt-6 {
  margin-top: 1.5rem;
}

.mt-4 {
  margin-top: 1rem;
}

.flex {
  display: flex;
}

.items-end {
  align-items: flex-end;
}

.w-full {
  width: 100%;
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

.text-success {
  color: #10b981;
}

.text-warning {
  color: #f59e0b;
}

.text-muted {
  color: #94a3b8;
}
</style>