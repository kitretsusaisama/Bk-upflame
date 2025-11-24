<template>
  <div class="loading-spinner" :class="sizeClass">
    <div class="spinner" :class="colorClass"></div>
    <div v-if="message" class="loading-message">{{ message }}</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  color: {
    type: String,
    default: 'primary', // 'primary', 'secondary', 'success', 'error', 'warning', 'info'
    validator: (value) => ['primary', 'secondary', 'success', 'error', 'warning', 'info'].includes(value)
  },
  message: {
    type: String,
    default: ''
  }
})

const sizeClass = computed(() => {
  return `loading-spinner--${props.size}`
})

const colorClass = computed(() => {
  return `spinner--${props.color}`
})
</script>

<style scoped>
.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-md);
}

.loading-spinner--sm .spinner {
  width: 20px;
  height: 20px;
  border-width: 2px;
}

.loading-spinner--md .spinner {
  width: 40px;
  height: 40px;
  border-width: 3px;
}

.loading-spinner--lg .spinner {
  width: 60px;
  height: 60px;
  border-width: 4px;
}

.spinner {
  border-radius: 50%;
  border-style: solid;
  border-color: var(--gray-200);
  border-top-color: var(--primary);
  animation: spin 1s linear infinite;
}

.spinner--primary {
  border-top-color: var(--primary);
}

.spinner--secondary {
  border-top-color: var(--secondary);
}

.spinner--success {
  border-top-color: var(--success);
}

.spinner--error {
  border-top-color: var(--error);
}

.spinner--warning {
  border-top-color: var(--warning);
}

.spinner--info {
  border-top-color: var(--info);
}

.loading-message {
  font-size: 0.875rem;
  color: var(--gray-600);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>