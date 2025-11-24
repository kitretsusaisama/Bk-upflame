<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Menu</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Menu Management</h1>
      <div class="page-actions">
        <button class="btn btn-primary" @click="showCreateMenuItemModal = true">
          <span>➕</span> Add Menu Item
        </button>
      </div>
    </div>
    
    <div class="grid grid-2">
      <Card title="Menu Structure">
        <div class="menu-tree">
          <div v-for="item in menuItems" :key="item.id" class="menu-item">
            <div class="menu-item-header">
              <div class="menu-item-info">
                <span class="menu-item-icon">{{ item.icon }}</span>
                <span class="menu-item-name">{{ item.name }}</span>
              </div>
              <div class="menu-item-actions">
                <button class="btn-icon" title="Edit">✏️</button>
                <button class="btn-icon" title="Delete">🗑️</button>
                <button class="btn-icon" title="Move Up">⬆️</button>
                <button class="btn-icon" title="Move Down">⬇️</button>
              </div>
            </div>
            
            <div v-if="item.children && item.children.length > 0" class="menu-item-children">
              <div v-for="child in item.children" :key="child.id" class="menu-item-child">
                <div class="menu-item-header">
                  <div class="menu-item-info">
                    <span class="menu-item-icon">{{ child.icon }}</span>
                    <span class="menu-item-name">{{ child.name }}</span>
                  </div>
                  <div class="menu-item-actions">
                    <button class="btn-icon" title="Edit">✏️</button>
                    <button class="btn-icon" title="Delete">🗑️</button>
                    <button class="btn-icon" title="Move Up">⬆️</button>
                    <button class="btn-icon" title="Move Down">⬇️</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Card>
      
      <div class="menu-sidebar">
        <Card title="Menu Preview">
          <div class="menu-preview">
            <div v-for="item in menuItems" :key="item.id" class="preview-item">
              <div class="preview-item-header">
                <span class="preview-item-icon">{{ item.icon }}</span>
                <span class="preview-item-name">{{ item.name }}</span>
              </div>
              
              <div v-if="item.children && item.children.length > 0" class="preview-item-children">
                <div v-for="child in item.children" :key="child.id" class="preview-item-child">
                  <span class="preview-item-name">{{ child.name }}</span>
                </div>
              </div>
            </div>
          </div>
        </Card>
        
        <Card title="Available Icons">
          <div class="icon-grid">
            <div v-for="icon in availableIcons" :key="icon" class="icon-item" @click="selectIcon(icon)">
              <span class="icon-preview">{{ icon }}</span>
            </div>
          </div>
        </Card>
      </div>
    </div>
    
    <!-- Create Menu Item Modal -->
    <div v-if="showCreateMenuItemModal" class="modal-overlay" @click="showCreateMenuItemModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Add Menu Item</h3>
          <button class="modal-close" @click="showCreateMenuItemModal = false">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createMenuItem">
            <div class="form-group">
              <label class="form-label">Name</label>
              <input type="text" class="form-input" v-model="menuItemForm.name" placeholder="Enter menu item name">
            </div>
            
            <div class="form-group">
              <label class="form-label">Icon</label>
              <div class="icon-selection">
                <span class="selected-icon">{{ menuItemForm.icon || 'Select an icon' }}</span>
                <button type="button" class="btn btn-outline btn-sm" @click="showIconPicker = !showIconPicker">
                  Choose Icon
                </button>
              </div>
              
              <div v-if="showIconPicker" class="icon-picker">
                <div class="icon-grid">
                  <div v-for="icon in availableIcons" :key="icon" class="icon-item" @click="selectMenuItemIcon(icon)">
                    <span class="icon-preview">{{ icon }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">URL</label>
              <input type="text" class="form-input" v-model="menuItemForm.url" placeholder="Enter URL or route">
            </div>
            
            <div class="form-group">
              <label class="form-label">Parent Menu</label>
              <select class="form-select" v-model="menuItemForm.parentId">
                <option value="">None (Top Level)</option>
                <option v-for="item in menuItems" :key="item.id" :value="item.id">{{ item.name }}</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Permission</label>
              <input type="text" class="form-input" v-model="menuItemForm.permission" placeholder="Enter required permission">
            </div>
            
            <div class="form-group">
              <label class="form-label">Order</label>
              <input type="number" class="form-input" v-model="menuItemForm.order" min="0">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showCreateMenuItemModal = false">Cancel</button>
          <button class="btn btn-primary" @click="createMenuItem">Create Menu Item</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'

// Modal states
const showCreateMenuItemModal = ref(false)
const showIconPicker = ref(false)

// Form data
const menuItemForm = reactive({
  name: '',
  icon: '📋',
  url: '',
  parentId: '',
  permission: '',
  order: 0
})

// Sample menu items data
const menuItems = ref([
  {
    id: 1,
    name: 'Dashboard',
    icon: '📊',
    url: '/dashboard',
    permission: null,
    children: []
  },
  {
    id: 2,
    name: 'Users',
    icon: '👥',
    url: '/users',
    permission: 'manage-users',
    children: []
  },
  {
    id: 3,
    name: 'Services',
    icon: '💼',
    url: '/services',
    permission: null,
    children: [
      {
        id: 4,
        name: 'Providers',
        icon: '👷',
        url: '/services/providers',
        permission: 'manage-providers'
      },
      {
        id: 5,
        name: 'Categories',
        icon: '📂',
        url: '/services/categories',
        permission: 'manage-categories'
      }
    ]
  },
  {
    id: 6,
    name: 'Settings',
    icon: '⚙️',
    url: '/settings',
    permission: 'manage-settings',
    children: []
  }
])

// Available icons
const availableIcons = [
  '📊', '👥', '💼', '📅', '💰', '⚙️', '📋', '📝', '🔍', '🔔',
  '👤', '🏠', '🛒', '📦', '🚚', '💳', '📊', '📈', '📉', '✅',
  '❌', '⚠️', 'ℹ️', '❓', '💬', '📧', '📞', '📍', '🕒', '📂'
]

// Form submission handler
function createMenuItem() {
  console.log('Creating menu item:', menuItemForm)
  // In a real app, you would send this data to your backend
  alert('Menu item created successfully!')
  
  // Reset form and close modal
  menuItemForm.name = ''
  menuItemForm.icon = '📋'
  menuItemForm.url = ''
  menuItemForm.parentId = ''
  menuItemForm.permission = ''
  menuItemForm.order = 0
  showCreateMenuItemModal.value = false
  showIconPicker.value = false
}

// Icon selection handlers
function selectIcon(icon) {
  // This would be used for the icon picker
  console.log('Selected icon:', icon)
}

function selectMenuItemIcon(icon) {
  menuItemForm.icon = icon
  showIconPicker.value = false
}
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.page-actions {
  display: flex;
  gap: 0.5rem;
}

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.menu-item {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  background-color: white;
}

.menu-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #f1f5f9;
}

.menu-item-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.menu-item-icon {
  font-size: 1.25rem;
}

.menu-item-name {
  font-weight: 500;
  color: #1e293b;
}

.menu-item-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  background-color: #f1f5f9;
  color: #64748b;
  text-decoration: none;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
}

.btn-icon:hover {
  background-color: #e2e8f0;
}

.menu-item-children {
  padding: 0.5rem 1rem;
  background-color: #f8fafc;
}

.menu-item-child {
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  margin-bottom: 0.5rem;
  background-color: white;
}

.menu-item-child:last-child {
  margin-bottom: 0;
}

.menu-sidebar {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.menu-preview {
  padding: 1rem 0;
}

.preview-item {
  margin-bottom: 1rem;
}

.preview-item-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  border-radius: 0.375rem;
  background-color: #f1f5f9;
  margin-bottom: 0.25rem;
}

.preview-item-icon {
  font-size: 1rem;
}

.preview-item-name {
  font-weight: 500;
  color: #1e293b;
}

.preview-item-children {
  padding-left: 2rem;
}

.preview-item-child {
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  background-color: #e2e8f0;
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
}

.preview-item-child:last-child {
  margin-bottom: 0;
}

.icon-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 0.5rem;
  max-height: 200px;
  overflow-y: auto;
  padding: 0.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
}

.icon-item {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem;
  border-radius: 0.375rem;
  cursor: pointer;
  background-color: #f8fafc;
  transition: all 0.2s ease;
}

.icon-item:hover {
  background-color: #e2e8f0;
  transform: scale(1.1);
}

.icon-preview {
  font-size: 1.25rem;
}

.icon-selection {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.selected-icon {
  font-size: 1.5rem;
}

.icon-picker {
  margin-top: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.form-input,
.form-select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #94a3b8;
  padding: 0;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #64748b;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .icon-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
</style>