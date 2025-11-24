import { ref, reactive } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query'

// Create a global query client instance
const queryClient = useQueryClient()

// Generic API error handler
const handleApiError = (error) => {
  console.error('API Error:', error)
  
  // Extract error message
  let message = 'An unexpected error occurred'
  
  if (error.response) {
    // Server responded with error status
    const { status, data } = error.response
    
    if (status === 401) {
      message = 'Your session has expired. Please log in again.'
      // Optionally redirect to login
      // window.location.href = '/login'
    } else if (status === 403) {
      message = 'You do not have permission to perform this action.'
    } else if (status === 422) {
      // Validation errors
      if (data.errors) {
        message = Object.values(data.errors).flat().join(', ')
      } else {
        message = 'Please check your input and try again.'
      }
    } else if (status >= 500) {
      message = 'Server error. Please try again later.'
    } else if (data.message) {
      message = data.message
    }
  } else if (error.request) {
    // Network error
    message = 'Network error. Please check your connection and try again.'
  } else {
    // Other errors
    message = error.message || message
  }
  
  return message
}

// Generic API request function
const apiRequest = async (url, options = {}) => {
  const { props } = usePage()
  const csrfToken = props.value.csrf_token
  
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      ...options.headers
    },
    credentials: 'same-origin',
    ...options
  }
  
  const response = await fetch(url, defaultOptions)
  
  if (!response.ok) {
    const error = new Error(`HTTP error! status: ${response.status}`)
    error.response = response
    throw error
  }
  
  return response.json()
}

// Composable for fetching data with vue-query
export const useApiQuery = (key, url, options = {}) => {
  return useQuery(
    key,
    async () => {
      const response = await apiRequest(url)
      return response.data || response
    },
    {
      refetchOnWindowFocus: false,
      retry: 1,
      ...options
    }
  )
}

// Composable for mutations (POST, PUT, DELETE)
export const useApiMutation = (url, options = {}) => {
  const queryClient = useQueryClient()
  
  return useMutation(
    async (data) => {
      const method = options.method || 'POST'
      const response = await apiRequest(url, {
        method,
        body: JSON.stringify(data)
      })
      return response.data || response
    },
    {
      onSuccess: (data) => {
        // Invalidate and refetch queries after mutation
        queryClient.invalidateQueries()
        
        if (options.onSuccess) {
          options.onSuccess(data)
        }
      },
      onError: (error) => {
        const message = handleApiError(error)
        
        if (options.onError) {
          options.onError(message)
        } else {
          // Default error handling
          console.error('Mutation error:', message)
        }
      },
      ...options
    }
  )
}

// Composable for paginated data
export const useApiPagination = (key, baseUrl, initialParams = {}) => {
  const params = reactive({
    page: 1,
    per_page: 15,
    ...initialParams
  })
  
  const query = useQuery(
    [...key, params],
    async () => {
      const url = new URL(baseUrl, window.location.origin)
      
      // Add pagination params to URL
      Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined) {
          url.searchParams.append(key, params[key])
        }
      })
      
      const response = await apiRequest(url.toString())
      return response
    },
    {
      keepPreviousData: true,
      refetchOnWindowFocus: false
    }
  )
  
  const setPage = (page) => {
    params.page = page
  }
  
  const setPerPage = (perPage) => {
    params.per_page = perPage
    params.page = 1 // Reset to first page
  }
  
  const setSearch = (search) => {
    params.search = search
    params.page = 1 // Reset to first page
  }
  
  return {
    ...query,
    params,
    setPage,
    setPerPage,
    setSearch
  }
}

// Composable for form handling
export const useApiForm = (initialData = {}) => {
  const formData = ref({ ...initialData })
  const errors = ref({})
  const processing = ref(false)
  const success = ref(false)
  
  const reset = () => {
    formData.value = { ...initialData }
    errors.value = {}
    processing.value = false
    success.value = false
  }
  
  const setField = (field, value) => {
    formData.value[field] = value
    // Clear error for this field when user starts typing
    if (errors.value[field]) {
      delete errors.value[field]
    }
  }
  
  const submit = async (url, options = {}) => {
    processing.value = true
    success.value = false
    errors.value = {}
    
    try {
      const method = options.method || 'POST'
      const response = await apiRequest(url, {
        method,
        body: JSON.stringify(formData.value)
      })
      
      success.value = true
      processing.value = false
      
      if (options.onSuccess) {
        options.onSuccess(response)
      }
      
      return response
    } catch (error) {
      processing.value = false
      
      if (error.response && error.response.status === 422) {
        // Validation errors
        const errorData = await error.response.json()
        if (errorData.errors) {
          errors.value = errorData.errors
        }
      } else {
        // Other errors
        const message = handleApiError(error)
        errors.value.form = message
      }
      
      if (options.onError) {
        options.onError(errors.value)
      }
      
      throw error
    }
  }
  
  return {
    formData,
    errors,
    processing,
    success,
    setField,
    submit,
    reset
  }
}

// Export the query client for use in other parts of the app
export { queryClient }