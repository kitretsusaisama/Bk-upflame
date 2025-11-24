<template>
  <aside class="sidebar" :class="{ 'active': isSidebarOpen }">
    <div class="sidebar-header">
      <div class="sidebar-brand">
        <span class="brand-icon">⚡</span>
        <span class="brand-text">{{ appName }}</span>
      </div>
      <button class="sidebar-toggle" @click="toggleSidebar">
        <span>☰</span>
      </button>
    </div>
    
    <nav class="sidebar-nav">
      <ul class="nav-list">
        <template v-for="item in menuItems" :key="item.id">
          <li v-if="item.type === 'heading'" class="nav-header">{{ item.label || item.name }}</li>
          <li v-else-if="item.type === 'separator'" class="nav-separator">{{ item.label || item.name }}</li>
          <li v-else class="nav-item" :class="{ 'active': isActive(item) }">
            <a :href="getItemUrl(item)" class="nav-link">
              <span v-if="item.icon" class="nav-icon">
                <i :class="item.icon"></i>
              </span>
              <span v-else class="nav-icon">●</span>
              <span class="nav-text">{{ item.label || item.name }}</span>
              <span v-if="item.metadata && item.metadata.badge" class="nav-badge">{{ item.metadata.badge }}</span>
            </a>
            
            <ul v-if="item.children && item.children.length > 0" class="nav-submenu">
              <li v-for="child in item.children" :key="child.id" class="nav-item" :class="{ 'active': isActive(child) }">
                <a :href="getItemUrl(child)" class="nav-link">
                  <span class="nav-text">{{ child.label || child.name }}</span>
                </a>
                
                <ul v-if="child.children && child.children.length > 0" class="nav-submenu">
                  <li v-for="grandchild in child.children" :key="grandchild.id" class="nav-item" :class="{ 'active': isActive(grandchild) }">
                    <a :href="getItemUrl(grandchild)" class="nav-link">
                      <span class="nav-text">{{ grandchild.label || grandchild.name }}</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
        </template>
        
        <!-- Show message if no menu items -->
        <li v-if="menuItems.length === 0" class="nav-item no-menu-item">
          <div class="nav-link">
            <span class="nav-text">No menu items available</span>
          </div>
        </li>
      </ul>
    </nav>
    
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="user-avatar">{{ getUserInitials() }}</div>
        <div class="user-info">
          <div class="user-name">{{ user.name || user.email }}</div>
          <div class="user-role">{{ userRole }}</div>
        </div>
      </div>
      
      <!-- Logout Button -->
      <button class="logout-btn" @click="logout">
        <span class="logout-icon">🚪</span>
        <span class="logout-text">Logout</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { computed } from 'vue'
import { router } from '@inertiajs/inertia-vue3'

const props = defineProps({
  menuItems: {
    type: Array,
    default: () => []
  },
  userRole: {
    type: String,
    default: 'User'
  },
  isSidebarOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['toggle-sidebar'])

const { props: pageProps } = usePage()
const appName = computed(() => pageProps.value.appName || 'App')
const user = computed(() => pageProps.value.auth?.user || {})

// Function to get item URL
function getItemUrl(item) {
  if (item.route) {
    // In a real app, you would use route() helper or similar
    return `/${item.route.replace(/\./g, '/')}`
  }
  return item.url || '#'
}

// Function to check if item is active
function isActive(item) {
  // In a real app, you would check against current route
  return false
}

// Function to get user initials
function getUserInitials() {
  if (user.value.name) {
    return user.value.name.charAt(0).toUpperCase()
  } else if (user.value.email) {
    return user.value.email.charAt(0).toUpperCase()
  }
  return 'U'
}

// Function to toggle sidebar
function toggleSidebar() {
  emit('toggle-sidebar')
}

// Function to logout
function logout() {
  if (confirm('Are you sure you want to logout?')) {
    router.post('/logout')
  }
}
</script>

<style scoped>
.sidebar {
  width: 250px;
  background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
  color: white;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(30, 41, 59, 0.8);
  backdrop-filter: blur(10px);
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 700;
  font-size: 1.25rem;
}

.brand-icon {
  font-size: 1.5rem;
}

.sidebar-toggle {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 1.5rem;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: background-color 0.2s;
}

.sidebar-toggle:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 0;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-header {
  padding: 0.75rem 1rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #94a3b8;
}

.nav-separator {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  margin: 0.5rem 0;
}

.nav-item {
  position: relative;
}

.nav-item.active > .nav-link {
  background-color: rgba(56, 134, 255, 0.2);
  color: #3b82f6;
  border-left: 3px solid #3b82f6;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #cbd5e1;
  text-decoration: none;
  transition: all 0.2s ease;
  position: relative;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.05);
  color: white;
}

.nav-icon {
  margin-right: 0.75rem;
  width: 1.5rem;
  text-align: center;
  font-size: 1rem;
}

.nav-text {
  flex: 1;
  font-size: 0.875rem;
  font-weight: 500;
}

.nav-badge {
  background-color: #ef4444;
  color: white;
  font-size: 0.7rem;
  padding: 0.125rem 0.5rem;
  border-radius: 9999px;
  font-weight: 600;
}

.nav-submenu {
  list-style: none;
  padding-left: 0;
  background-color: rgba(0, 0, 0, 0.2);
}

.nav-submenu .nav-item {
  border-left: 3px solid transparent;
}

.nav-submenu .nav-item.active {
  border-left-color: #3b82f6;
}

.nav-submenu .nav-link {
  padding-left: 3rem;
  font-size: 0.8125rem;
  color: #94a3b8;
}

.nav-submenu .nav-submenu .nav-link {
  padding-left: 4rem;
  font-size: 0.75rem;
}

.no-menu-item {
  padding: 1rem;
  text-align: center;
  color: #94a3b8;
  font-style: italic;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(30, 41, 59, 0.8);
  backdrop-filter: blur(10px);
}

.sidebar-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.user-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 0.875rem;
}

.user-role {
  font-size: 0.75rem;
  color: #94a3b8;
}

.logout-btn {
  width: 100%;
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  background: none;
  border: none;
  color: #cbd5e1;
  cursor: pointer;
  text-align: left;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  font-weight: 500;
}

.logout-btn:hover {
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.logout-icon {
  margin-right: 0.75rem;
  width: 1.5rem;
  text-align: center;
}

.logout-text {
  flex: 1;
}

/* Mobile styles */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar.active {
    transform: translateX(0);
  }
}

/* Tablet styles */
@media (max-width: 1024px) {
  .sidebar {
    width: 220px;
  }
  
  .sidebar-brand {
    font-size: 1.1rem;
  }
  
  .nav-text {
    font-size: 0.8125rem;
  }
}

/* Small mobile styles */
@media (max-width: 480px) {
  .sidebar {
    width: 200px;
  }
  
  .sidebar-brand span {
    display: none;
  }
  
  .sidebar-brand .brand-icon {
    margin-right: 0;
  }
  
  .user-name,
  .user-role {
    display: none;
  }
  
  .user-avatar {
    width: 2rem;
    height: 2rem;
  }
}

/* Large desktop styles */
@media (min-width: 1440px) {
  .sidebar {
    width: 270px;
  }
  
  .nav-text {
    font-size: 0.9375rem;
  }
}
</style>