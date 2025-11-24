import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

/**
 * Composable for checking user permissions and roles
 * 
 * @returns {Object} Object with can and is functions
 */
export function usePermissions() {
  const page = usePage()
  const perms = computed(() => page.props.value.auth?.permissions ?? [])
  const roles = computed(() => page.props.value.auth?.roles ?? [])

  /**
   * Check if user has a specific permission
   * 
   * @param {string} permission - Permission name to check
   * @returns {boolean} True if user has permission or if no permission specified
   */
  function can(permission) {
    if (!permission) return true
    return perms.value.includes(permission)
  }

  /**
   * Check if user has a specific role
   * 
   * @param {string} roleName - Role name to check
   * @returns {boolean} True if user has role
   */
  function is(roleName) {
    return roles.value.includes(roleName)
  }

  return { can, is }
}