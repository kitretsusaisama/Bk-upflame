<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Test</span> <span class="breadcrumb-sep">/</span> <span>Logout</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Logout Test</h1>
    </div>
    
    <Card title="User Information">
      <p><strong>User:</strong> {{ user.email }}</p>
      <p><strong>User ID:</strong> {{ user.id }}</p>
      <button @click="testLogout" class="btn btn-danger">Test Logout</button>
      <button @click="testGetLogout" class="btn btn-secondary">Test GET Logout</button>
    </Card>
    
    <Card title="Debug Info" class="mt-4">
      <p><strong>CSRF Token:</strong> {{ csrfToken }}</p>
      <p><strong>Status:</strong> {{ status }}</p>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { router } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Props from Inertia
const { props } = usePage()
const user = props.value.auth?.user || {}

// Reactive data
const status = ref('Ready')
const csrfToken = ref('')

// Get CSRF token from meta tag
const getCsrfToken = () => {
  const meta = document.head.querySelector('meta[name="csrf-token"]')
  return meta ? meta.content : null
}

csrfToken.value = getCsrfToken()

function testLogout() {
  status.value = 'Logging out...'
  
  if (confirm('Are you sure you want to logout?')) {
    console.log('Attempting to logout with POST...')
    
    // Method 1: Using Inertia router
    router.post('/logout', {}, {
      onSuccess: () => {
        console.log('Logout successful')
        status.value = 'Logout successful'
      },
      onError: (errors) => {
        console.error('Logout failed:', errors)
        status.value = 'Logout failed: ' + JSON.stringify(errors)
      },
      onFinish: () => {
        console.log('Logout request finished')
      }
    })
  }
}

function testGetLogout() {
  status.value = 'Testing GET logout...'
  
  // Method 2: Using fetch API with GET request
  fetch('/logout', {
    method: 'GET',
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
    },
    credentials: 'same-origin'
  })
  .then(response => {
    if (response.ok) {
      console.log('GET logout successful')
      status.value = 'GET logout successful'
      // Redirect to login page
      window.location.href = '/login'
    } else {
      console.error('GET logout failed:', response.status)
      status.value = 'GET logout failed: ' + response.status
    }
  })
  .catch(error => {
    console.error('GET logout error:', error)
    status.value = 'GET logout error: ' + error.message
  })
}
</script>

<style scoped>
.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  border: none;
  margin-right: 0.5rem;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
}

.btn-danger:hover {
  background-color: #dc2626;
}

.btn-secondary {
  background-color: #64748b;
  color: white;
}

.btn-secondary:hover {
  background-color: #475569;
}

.mt-4 {
  margin-top: 1rem;
}
</style>