<template>
  <div class="table-container">
    <table class="table">
      <thead v-if="headers.length > 0">
        <tr>
          <th v-for="(header, index) in headers" :key="index" class="table-header">
            {{ header }}
          </th>
        </tr>
      </thead>
      <tbody>
        <slot></slot>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  headers: {
    type: Array,
    default: () => []
  }
})
</script>

<style scoped>
.table-container {
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table-header {
  background-color: #f1f5f9;
  padding: 0.75rem 1rem;
  text-align: left;
  font-weight: 600;
  color: #1e293b;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.table-header:first-child {
  border-top-left-radius: 0.25rem;
}

.table-header:last-child {
  border-top-right-radius: 0.25rem;
}

.table td {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #e2e8f0;
  color: #334155;
}

.table tr:last-child td {
  border-bottom: none;
}

.table tr:hover td {
  background-color: #f8fafc;
}
</style>