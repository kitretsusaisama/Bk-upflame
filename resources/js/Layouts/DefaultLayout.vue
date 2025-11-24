<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold">{{ appName }}</h1>
            </div>
          </div>
          <div class="flex items-center">
            <div v-if="auth.user" class="ml-3 relative">
              <div class="text-sm text-gray-700">
                {{ auth.user.name }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const appName = props.value.appName || 'App'
const auth = props.value.auth || {}
</script>