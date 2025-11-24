<template>
  <div class="progress-container">
    <div class="progress-bar">
      <div 
        class="progress-fill" 
        :class="colorClass"
        :style="{ width: `${progress}%` }"
      ></div>
    </div>
    <div v-if="showPercentage" class="progress-percentage">
      {{ Math.round(progress) }}%
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  progress: {
    type: Number,
    default: 0,
    validator: (value) => value >= 0 && value <= 100
  },
  color: {
    type: String,
    default: 'primary', // 'primary', 'secondary', 'success', 'error', 'warning', 'info'
    validator: (value) => ['primary', 'secondary', 'success', 'error', 'warning', 'info'].includes(value)
  },
  showPercentage: {
    type: Boolean,
    default: false
  }
})

const colorClass = computed(() => {
  return `progress-fill--${props.color}`
})
</script>

<style scoped>
.progress-container {
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
}

.progress-bar {
  flex: 1;
  height: 8px;
  background: var(--gray-200);
  border-radius: 1rem;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: var(--primary);
  border-radius: 1rem;
  transition: width 0.3s ease;
}

.progress-fill--primary {
  background: var(--primary);
}

.progress-fill--secondary {
  background: var(--secondary);
}

.progress-fill--success {
  background: var(--success);
}

.progress-fill--error {
  background: var(--error);
}

.progress-fill--warning {
  background: var(--warning);
}

.progress-fill--info {
  background: var(--info);
}

.progress-percentage {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--gray-700);
  min-width: 40px;
  text-align: right;
}
</style>