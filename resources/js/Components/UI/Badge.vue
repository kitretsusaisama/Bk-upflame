<template>
  <span class="badge" :class="`badge-${type}`">
    <slot></slot>
  </span>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'default',
    validator: (value) => [
      'default', 'primary', 'secondary', 'success', 'warning', 'error', 'info'
    ].includes(value)
  }
})
</script>

<style scoped>
.badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
  border-radius: 0.375rem;
  line-height: 1;
}

.badge-default {
  background-color: #e2e8f0;
  color: #1e293b;
}

.badge-primary {
  background-color: #dbeafe;
  color: #1d4ed8;
}

.badge-secondary {
  background-color: #e5e7eb;
  color: #374151;
}

.badge-success {
  background-color: #dcfce7;
  color: #166534;
}

.badge-warning {
  background-color: #fef3c7;
  color: #92400e;
}

.badge-error {
  background-color: #fee2e2;
  color: #991b1b;
}

.badge-info {
  background-color: #cffafe;
  color: #0e7490;
}
</style>