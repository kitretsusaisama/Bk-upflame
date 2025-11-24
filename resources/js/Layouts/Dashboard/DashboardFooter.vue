<template>
  <footer class="dashboard-footer">
    <div class="footer-content">
      <p>&copy; {{ currentYear }} {{ appName }}. All rights reserved.</p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const appName = computed(() => props.value.appName || 'App')
const currentYear = computed(() => new Date().getFullYear())
</script>

<style scoped>
.dashboard-footer {
  background-color: white;
  border-top: 1px solid #e2e8f0;
  padding: 1rem 1.5rem;
}

.footer-content {
  text-align: center;
  color: #64748b;
  font-size: 0.875rem;
}
</style>