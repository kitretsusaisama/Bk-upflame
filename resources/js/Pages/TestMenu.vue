<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Test</span> <span class="breadcrumb-sep">/</span> <span>Menu Data</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Menu Data Test</h1>
    </div>
    
    <Card title="Menu Data">
      <pre>{{ JSON.stringify(menuData, null, 2) }}</pre>
    </Card>
    
    <Card title="User Data" class="mt-6">
      <pre>{{ JSON.stringify(userData, null, 2) }}</pre>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Props from Inertia
const { props } = usePage()

// Data
const menuData = ref(props.value.menu || [])
const userData = ref({
  auth: props.value.auth || {},
  roles: props.value.auth?.roles || [],
  permissions: props.value.auth?.permissions || []
})

// Log to console for debugging
console.log('Menu Data:', menuData.value)
console.log('User Data:', userData.value)
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

.mt-6 {
  margin-top: 1.5rem;
}

pre {
  background-color: #f8fafc;
  border-radius: 0.5rem;
  padding: 1rem;
  overflow-x: auto;
  font-size: 0.875rem;
  line-height: 1.5;
}
</style>