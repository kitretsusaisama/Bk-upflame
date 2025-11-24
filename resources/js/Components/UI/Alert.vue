<template>
  <div v-if="show" class="alert" :class="`alert-${type}`">
    <div class="alert-content">
      <span v-if="icon" class="alert-icon">{{ icon }}</span>
      <div class="alert-message">
        <slot></slot>
      </div>
      <button v-if="dismissible" class="alert-close" @click="dismiss">
        <span>×</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'info',
    validator: (value) => [
      'info', 'success', 'warning', 'error'
    ].includes(value)
  },
  icon: {
    type: String,
    default: ''
  },
  dismissible: {
    type: Boolean,
    default: false
  },
  modelValue: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['update:modelValue'])

const show = ref(props.modelValue)

function dismiss() {
  show.value = false
  emit('update:modelValue', false)
}
</script>

<style scoped>
.alert {
  border-radius: 0.375rem;
  padding: 1rem;
  margin-bottom: 1rem;
}

.alert-info {
  background-color: #dbeafe;
  border: 1px solid #93c5fd;
  color: #1e40af;
}

.alert-success {
  background-color: #dcfce7;
  border: 1px solid #86efac;
  color: #166534;
}

.alert-warning {
  background-color: #fef3c7;
  border: 1px solid #fde68a;
  color: #92400e;
}

.alert-error {
  background-color: #fee2e2;
  border: 1px solid #fca5a5;
  color: #991b1b;
}

.alert-content {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
}

.alert-icon {
  font-size: 1.25rem;
}

.alert-message {
  flex: 1;
}

.alert-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: inherit;
  opacity: 0.7;
  padding: 0;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.alert-close:hover {
  opacity: 1;
}
</style>