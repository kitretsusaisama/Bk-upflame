<template>
  <div class="flex space-x-2">
    <button 
      v-if="can('manage-users')" 
      class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
    >
      Edit User
    </button>
    
    <button 
      v-if="can('delete-users')" 
      class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
      @click="deleteUser"
    >
      Delete User
    </button>
    
    <button 
      v-if="is('Super Admin')" 
      class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
    >
      Admin Actions
    </button>
  </div>
</template>

<script setup>
import { usePermissions } from '@/plugins/permissions'

// Define props
const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

// Get permissions composable
const { can, is } = usePermissions()

// Define emit for events
const emit = defineEmits(['delete'])

// Delete user function
function deleteUser() {
  if (confirm(`Are you sure you want to delete ${props.user.name}?`)) {
    emit('delete', props.user.id)
  }
}
</script>