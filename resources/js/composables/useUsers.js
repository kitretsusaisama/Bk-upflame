import { useQuery } from '@tanstack/vue-query'
import axios from 'axios'

/**
 * Composable for fetching users with Vue Query
 * 
 * @param {Object} params - Query parameters
 * @returns {Object} Vue Query result
 */
export function useUsers(params = {}) {
  return useQuery({
    queryKey: ['users', params],
    queryFn: async () => {
      const { data } = await axios.get('/api/users', { params })
      return data
    },
    keepPreviousData: true,
    staleTime: 10000,
  })
}