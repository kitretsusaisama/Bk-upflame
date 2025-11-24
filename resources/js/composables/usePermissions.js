import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

// Composable for handling permissions and RBAC
export const usePermissions = () => {
  const { props } = usePage()
  
  // Get user permissions from Inertia props
  const permissions = computed(() => {
    return props.value.auth?.user?.permissions || []
  })
  
  // Get user roles from Inertia props
  const roles = computed(() => {
    return props.value.auth?.user?.roles || []
  })
  
  // Get user role from Inertia props
  const userRole = computed(() => {
    return props.value.userRole || 'User'
  })
  
  // Check if user has a specific permission
  const hasPermission = (permission) => {
    return permissions.value.includes(permission)
  }
  
  // Check if user has any of the specified permissions
  const hasAnyPermission = (permissionsArray) => {
    return permissionsArray.some(permission => hasPermission(permission))
  }
  
  // Check if user has all of the specified permissions
  const hasAllPermissions = (permissionsArray) => {
    return permissionsArray.every(permission => hasPermission(permission))
  }
  
  // Check if user has a specific role
  const hasRole = (role) => {
    return roles.value.includes(role)
  }
  
  // Check if user has any of the specified roles
  const hasAnyRole = (rolesArray) => {
    return rolesArray.some(role => hasRole(role))
  }
  
  // Check if user has all of the specified roles
  const hasAllRoles = (rolesArray) => {
    return rolesArray.every(role => hasRole(role))
  }
  
  // Predefined role checks
  const isSuperAdmin = computed(() => {
    return userRole.value === 'Super Admin'
  })
  
  const isTenantAdmin = computed(() => {
    return userRole.value === 'Tenant Admin'
  })
  
  const isProvider = computed(() => {
    return userRole.value === 'Provider'
  })
  
  const isCustomer = computed(() => {
    return userRole.value === 'Customer'
  })
  
  const isOperations = computed(() => {
    return userRole.value === 'Operations'
  })
  
  // Check if user can access a specific dashboard section
  const canAccessDashboard = (dashboard) => {
    // Super admins can access everything
    if (isSuperAdmin.value) {
      return true
    }
    
    // Role-based dashboard access
    const dashboardAccess = {
      'customer': ['Customer'],
      'tenantadmin': ['Tenant Admin'],
      'provider': ['Provider'],
      'ops': ['Operations'],
      'superadmin': ['Super Admin']
    }
    
    const allowedRoles = dashboardAccess[dashboard.toLowerCase()] || []
    return hasAnyRole(allowedRoles)
  }
  
  // Check if user can perform specific actions
  const canCreate = (resource) => {
    if (isSuperAdmin.value) return true
    
    const createPermissions = {
      'users': ['create-users', 'manage-users'],
      'tenants': ['create-tenants', 'manage-tenants'],
      'providers': ['create-providers', 'manage-providers'],
      'bookings': ['create-bookings', 'manage-bookings'],
      'roles': ['create-roles', 'manage-roles']
    }
    
    const requiredPermissions = createPermissions[resource] || [`create-${resource}`]
    return hasAnyPermission(requiredPermissions)
  }
  
  const canEdit = (resource) => {
    if (isSuperAdmin.value) return true
    
    const editPermissions = {
      'users': ['edit-users', 'manage-users'],
      'tenants': ['edit-tenants', 'manage-tenants'],
      'providers': ['edit-providers', 'manage-providers'],
      'bookings': ['edit-bookings', 'manage-bookings'],
      'roles': ['edit-roles', 'manage-roles']
    }
    
    const requiredPermissions = editPermissions[resource] || [`edit-${resource}`]
    return hasAnyPermission(requiredPermissions)
  }
  
  const canDelete = (resource) => {
    if (isSuperAdmin.value) return true
    
    const deletePermissions = {
      'users': ['delete-users', 'manage-users'],
      'tenants': ['delete-tenants', 'manage-tenants'],
      'providers': ['delete-providers', 'manage-providers'],
      'bookings': ['delete-bookings', 'manage-bookings'],
      'roles': ['delete-roles', 'manage-roles']
    }
    
    const requiredPermissions = deletePermissions[resource] || [`delete-${resource}`]
    return hasAnyPermission(requiredPermissions)
  }
  
  const canView = (resource) => {
    if (isSuperAdmin.value) return true
    
    const viewPermissions = {
      'users': ['view-users', 'manage-users'],
      'tenants': ['view-tenants', 'manage-tenants'],
      'providers': ['view-providers', 'manage-providers'],
      'bookings': ['view-bookings', 'manage-bookings'],
      'reports': ['view-reports'],
      'logs': ['view-logs']
    }
    
    const requiredPermissions = viewPermissions[resource] || [`view-${resource}`]
    return hasAnyPermission(requiredPermissions)
  }
  
  return {
    // Reactive properties
    permissions,
    roles,
    userRole,
    isSuperAdmin,
    isTenantAdmin,
    isProvider,
    isCustomer,
    isOperations,
    
    // Permission checking methods
    hasPermission,
    hasAnyPermission,
    hasAllPermissions,
    hasRole,
    hasAnyRole,
    hasAllRoles,
    
    // Dashboard access
    canAccessDashboard,
    
    // CRUD permissions
    canCreate,
    canEdit,
    canDelete,
    canView
  }
}