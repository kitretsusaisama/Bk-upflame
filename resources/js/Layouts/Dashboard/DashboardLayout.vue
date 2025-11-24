<template>
  <div class="dashboard-wrapper">

    <!-- Sidebar -->
    <DashboardSidebar
      :menu-items="menu"
      :user-role="userRole"
      :is-sidebar-open="isSidebarOpen"
      @toggle-sidebar="toggleSidebar"
      ref="sidebarRef"
    />

    <!-- Main -->
    <div class="dashboard-main">
      
      <DashboardTopbar
        :user="authUser"
        @toggle-sidebar="toggleSidebar"
        ref="topbarRef"
      />

      <main class="dashboard-content">
        
        <!-- Flash Success -->
        <div v-if="flashSuccess" class="alert alert-success">
          {{ flashSuccess }}
        </div>

        <!-- Flash Error -->
        <div v-if="flashError" class="alert alert-error">
          {{ flashError }}
        </div>

        <!-- Page slot -->
        <slot />
      </main>

      <DashboardFooter />

    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

import DashboardSidebar from './DashboardSidebar.vue'
import DashboardTopbar from './DashboardTopbar.vue'
import DashboardFooter from './DashboardFooter.vue'

/* -------------------------------------------------
  INERTIA PROPS — SAFELY COMPUTED
---------------------------------------------------*/
const page = usePage()

const authUser = computed(() => page.props.auth?.user ?? {})
const menu = computed(() => page.props.menu ?? [])
const roles = computed(() => page.props.auth?.roles ?? [])
const userRole = computed(() => roles.value[0] ?? 'User')

/* Flash messages may be strings OR functions */
const flashSuccess = computed(() => {
  const f = page.props.flash?.success
  return typeof f === 'function' ? f() : f
})

const flashError = computed(() => {
  const f = page.props.flash?.error
  return typeof f === 'function' ? f() : f
})

/* -------------------------------------------------
  SIDEBAR OPEN/CLOSE
---------------------------------------------------*/
const isSidebarOpen = ref(false)
const sidebarRef = ref(null)
const topbarRef = ref(null)

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

/* Close sidebar when clicking outside (mobile only) */
const handleClickOutside = (e) => {
  if (window.innerWidth > 768) return

  const sidebar = sidebarRef.value?.$el
  const topbar = topbarRef.value?.$el

  if (!sidebar || !topbar) return

  const clickedInsideSidebar = sidebar.contains(e.target)
  const clickedToggleBtn = topbar.contains(e.target)

  if (!clickedInsideSidebar && !clickedToggleBtn) {
    isSidebarOpen.value = false
  }
}

/* Responsive behavior */
const handleResize = () => {
  if (window.innerWidth > 768) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('resize', handleResize)
})
</script>


<style scoped>
.dashboard-wrapper {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
}

.dashboard-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: 250px;
  transition: margin-left 0.3s ease;
}

.dashboard-content {
  flex: 1;
  padding: 2rem;
  margin-top: 64px;
}

.alert {
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 0.5rem;
  border: 1px solid;
  animation: slideIn 0.2s ease-out;
}

.alert-success {
  background: rgba(16, 185, 129, 0.1);
  border-color: #10b981;
  color: #065f46;
}

.alert-error {
  background: rgba(239, 68, 68, 0.1);
  border-color: #ef4444;
  color: #991b1b;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
}
  
/* Responsive fixes */
@media (max-width: 1024px) {
  .dashboard-main {
    margin-left: 220px;
  }
}

@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
  }

  .dashboard-content {
    padding: 1rem;
    margin-top: 56px;
  }
}

@media (max-width: 480px) {
  .dashboard-content {
    padding: 0.75rem;
  }
}

@media (min-width: 1440px) {
  .dashboard-main {
    margin-left: 270px;
  }

  .dashboard-content {
    padding: 2.5rem;
  }
}
</style>
