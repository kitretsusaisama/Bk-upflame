<template>
  <header class="topbar">
    <div class="topbar-left">
      <button class="mobile-menu-toggle" @click="toggleMobileMenu">
        <span>☰</span>
      </button>
      <nav class="breadcrumb">
        <slot name="breadcrumb"></slot>
      </nav>
    </div>
    
    <div class="topbar-right">
      <div class="topbar-search">
        <input type="text" placeholder="Search..." class="search-input">
        <span class="search-icon">🔍</span>
      </div>
      
      <div class="header-actions">
        <button class="action-button notification-btn">
          <span>🔔</span>
          <span class="badge notification-badge">3</span>
        </button>
        
        <div class="topbar-user" ref="userMenuRef">
          <button class="user-btn" @click="toggleUserMenu">
            <div class="user-avatar-small">{{ getUserInitials() }}</div>
            <span class="user-name-small">{{ user.name || user.email }}</span>
            <span class="dropdown-arrow">▼</span>
          </button>
          
          <div v-if="showUserMenu" class="user-dropdown">
            <a href="/profile" class="dropdown-item">
              <span class="dropdown-icon">👤</span>
              <span>Profile</span>
            </a>
            <a href="/settings" class="dropdown-item">
              <span class="dropdown-icon">⚙️</span>
              <span>Settings</span>
            </a>
            <hr class="dropdown-divider">
            <button @click="logout" class="dropdown-item">
              <span class="dropdown-icon">🚪</span>
              <span>Logout</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/inertia-vue3'

const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['toggle-sidebar'])

const showUserMenu = ref(false)
const userMenuRef = ref(null)

// Function to get user initials
function getUserInitials() {
  if (props.user.name) {
    return props.user.name.charAt(0).toUpperCase()
  } else if (props.user.email) {
    return props.user.email.charAt(0).toUpperCase()
  }
  return 'U'
}

// Function to toggle mobile menu
function toggleMobileMenu() {
  // Emit event to parent component to toggle sidebar
  emit('toggle-sidebar')
}

// Function to toggle user menu
function toggleUserMenu() {
  showUserMenu.value = !showUserMenu.value
}

// Function to logout
function logout() {
  if (confirm('Are you sure you want to logout?')) {
    router.post('/logout')
  }
}

// Close user menu when clicking outside
const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    showUserMenu.value = false
  }
}

// Add event listener for clicking outside
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

// Clean up event listener when component is unmounted
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.topbar {
  position: fixed;
  top: 0;
  left: 250px;
  right: 0;
  height: 64px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
  z-index: 999;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0.5rem;
  color: #64748b;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
}

.mobile-menu-toggle:hover {
  background-color: #f1f5f9;
  color: #1e293b;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
  font-weight: 500;
}

.breadcrumb-sep {
  color: #cbd5e1;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.topbar-search {
  position: relative;
}

.search-input {
  width: 300px;
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
  background-color: #f8fafc;
}

.search-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  background-color: white;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
}

.topbar-notifications {
  position: relative;
}

.notification-btn {
  background: none;
  border: none;
  position: relative;
  cursor: pointer;
  padding: 0.5rem;
  font-size: 1.25rem;
  color: #64748b;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
}

.notification-btn:hover {
  background: #f1f5f9;
  color: #1e293b;
}

.notification-badge {
  position: absolute;
  top: 0;
  right: 0;
  background: #ef4444;
  color: white;
  font-size: 0.625rem;
  padding: 0.125rem 0.375rem;
  border-radius: 1rem;
  font-weight: 600;
}

.topbar-user {
  position: relative;
}

.user-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.user-btn:hover {
  background: #f1f5f9;
}

.user-avatar-small {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-name-small {
  font-size: 0.875rem;
  font-weight: 500;
  color: #1e293b;
}

.dropdown-arrow {
  font-size: 0.75rem;
  color: #94a3b8;
}

.user-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 0.5rem;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  width: 220px;
  z-index: 1001;
  overflow: hidden;
  animation: dropdownFade 0.2s ease-out;
}

@keyframes dropdownFade {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  color: #1e293b;
  text-decoration: none;
  transition: all 0.2s ease;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 500;
}

.dropdown-item:hover {
  background: #f1f5f9;
}

.dropdown-icon {
  font-size: 1.125rem;
  width: 1.5rem;
  text-align: center;
}

.dropdown-divider {
  height: 1px;
  background: #e2e8f0;
  margin: 0.25rem 0;
}

/* Tablet styles */
@media (max-width: 1024px) {
  .topbar {
    left: 220px;
    padding: 0 1.5rem;
  }
  
  .search-input {
    width: 200px;
  }
}

/* Mobile styles */
@media (max-width: 768px) {
  .mobile-menu-toggle {
    display: block;
  }
  
  .topbar {
    left: 0;
    padding: 0 1rem;
    height: 56px;
  }
  
  .topbar-search {
    display: none;
  }
  
  .search-input {
    width: 150px;
  }
  
  .notification-badge {
    font-size: 0.5rem;
    padding: 0.1rem 0.25rem;
  }
}

/* Small mobile styles */
@media (max-width: 480px) {
  .topbar {
    padding: 0 0.75rem;
  }
  
  .user-name-small {
    display: none;
  }
  
  .dropdown-arrow {
    display: none;
  }
  
  .user-btn {
    padding: 0.25rem;
  }
  
  .user-avatar-small {
    width: 28px;
    height: 28px;
    font-size: 0.75rem;
  }
}

/* Large desktop styles */
@media (min-width: 1440px) {
  .topbar {
    left: 270px;
    padding: 0 2.5rem;
  }
  
  .search-input {
    width: 350px;
  }
}
</style>