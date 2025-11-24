<template>
  <div class="stat-card">
    <div class="stat-icon" :class="iconClass">
      {{ icon }}
    </div>
    <div class="stat-content">
      <div class="stat-value">{{ value }}</div>
      <div class="stat-label">{{ label }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  icon: {
    type: String,
    default: '📊'
  },
  value: {
    type: [String, Number],
    default: '0'
  },
  label: {
    type: String,
    default: 'Statistic'
  },
  iconClass: {
    type: String,
    default: 'stat-icon-primary'
  }
})
</script>

<style scoped>
.stat-card {
  display: flex;
  align-items: center;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 1rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 3rem;
  height: 3rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-right: 1rem;
}

.stat-icon-primary {
  background-color: #dbeafe;
  color: #3b82f6;
}

.stat-icon-info {
  background-color: #cffafe;
  color: #06b6d4;
}

.stat-icon-success {
  background-color: #dcfce7;
  color: #10b981;
}

.stat-icon-warning {
  background-color: #fef3c7;
  color: #f59e0b;
}

.stat-icon-error {
  background-color: #fee2e2;
  color: #ef4444;
}

.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.875rem;
  color: #64748b;
}
</style>