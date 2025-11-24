<template>
  <Layout>
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-2xl font-bold mb-4">Users Management</h2>
      <p class="mb-4">Welcome to the Users Management page powered by Inertia.js and Vue 3!</p>
      
      <div v-if="users.data && users.data.length > 0">
        <ul class="divide-y divide-gray-200">
          <li v-for="user in users.data" :key="user.id" class="py-4">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                  {{ user.name.charAt(0) }}
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                  {{ user.name }}
                </p>
                <p class="text-sm text-gray-500 truncate">
                  {{ user.email }}
                </p>
              </div>
              <div>
                <UserActions :user="user" @delete="handleDeleteUser" />
              </div>
            </div>
          </li>
        </ul>
      </div>
      
      <div v-else>
        <p class="text-gray-500">No users found.</p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/DefaultLayout.vue'
import UserActions from '@/Components/UserActions.vue'
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const users = props.value.users || { data: [] }

// Handle user deletion
function handleDeleteUser(userId) {
  console.log('Deleting user:', userId)
  // In a real app, you would make an API call here
  alert(`User ${userId} would be deleted in a real application`)
}
</script>