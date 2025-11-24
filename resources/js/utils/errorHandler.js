// Utility functions for handling errors in Vue components

// Handle API errors and show appropriate messages
export const handleApiError = (error, defaultMessage = 'An unexpected error occurred') => {
  console.error('API Error:', error)
  
  // Extract error message
  let message = defaultMessage
  
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
        // Combine all validation errors into a single message
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

// Show error notification
export const showErrorNotification = (message, duration = 5000) => {
  // In a real implementation, this would integrate with a notification system
  console.error('Error:', message)
  
  // Example using a simple alert (replace with your notification system)
  alert(`Error: ${message}`)
  
  // Or if you have a notification component:
  // Notification.error({
  //   title: 'Error',
  //   message: message,
  //   duration: duration
  // })
}

// Show success notification
export const showSuccessNotification = (message, duration = 3000) => {
  // In a real implementation, this would integrate with a notification system
  console.log('Success:', message)
  
  // Example using a simple alert (replace with your notification system)
  alert(`Success: ${message}`)
  
  // Or if you have a notification component:
  // Notification.success({
  //   title: 'Success',
  //   message: message,
  //   duration: duration
  // })
}

// Handle form submission errors
export const handleFormError = (error, formInstance = null) => {
  const message = handleApiError(error)
  
  // If we have a form instance, set the errors
  if (formInstance && error.response && error.response.status === 422) {
    const { data } = error.response
    if (data.errors) {
      // Set validation errors on the form
      Object.keys(data.errors).forEach(field => {
        if (formInstance.setFieldError) {
          formInstance.setFieldError(field, data.errors[field][0])
        }
      })
    }
  }
  
  showErrorNotification(message)
  return message
}

// Global error handler for Vue components
export const globalErrorHandler = (error, vm, info) => {
  console.error('Global error:', error, info)
  
  // Handle specific error types
  if (error instanceof Error) {
    if (error.message.includes('Network Error')) {
      showErrorNotification('Network error. Please check your connection.')
    } else if (error.message.includes('timeout')) {
      showErrorNotification('Request timeout. Please try again.')
    } else {
      showErrorNotification('An unexpected error occurred. Please try again.')
    }
  } else {
    showErrorNotification('An unexpected error occurred. Please try again.')
  }
}

// Export all functions
export default {
  handleApiError,
  showErrorNotification,
  showSuccessNotification,
  handleFormError,
  globalErrorHandler
}