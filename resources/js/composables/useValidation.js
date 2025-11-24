import { ref, reactive, computed } from 'vue'

// Validation rules
const rules = {
  required: (value) => !!value || value === 0 || 'This field is required',
  email: (value) => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    return pattern.test(value) || 'Invalid email address'
  },
  min: (min) => (value) => (value && value.length >= min) || `Minimum ${min} characters required`,
  max: (max) => (value) => (value && value.length <= max) || `Maximum ${max} characters allowed`,
  numeric: (value) => !isNaN(value) || 'Must be a number',
  integer: (value) => Number.isInteger(Number(value)) || 'Must be an integer',
  positive: (value) => Number(value) > 0 || 'Must be a positive number',
  phone: (value) => {
    const pattern = /^[\+]?[1-9][\d]{0,15}$/
    return pattern.test(value) || 'Invalid phone number'
  },
  url: (value) => {
    try {
      new URL(value)
      return true
    } catch (_) {
      return 'Invalid URL'
    }
  },
  minLength: (min) => (value) => (value && value.length >= min) || `Minimum ${min} characters required`,
  maxLength: (max) => (value) => (value && value.length <= max) || `Maximum ${max} characters allowed`
}

// Composable for form validation
export const useValidation = () => {
  const errors = reactive({})
  const touched = reactive({})
  
  // Validate a single field
  const validateField = (field, value, fieldRules) => {
    if (!Array.isArray(fieldRules)) {
      fieldRules = [fieldRules]
    }
    
    for (const rule of fieldRules) {
      // Handle function rules
      if (typeof rule === 'function') {
        const result = rule(value)
        if (result !== true) {
          errors[field] = result
          return false
        }
      }
      // Handle string rules (predefined)
      else if (typeof rule === 'string') {
        const ruleFunction = rules[rule]
        if (ruleFunction) {
          const result = ruleFunction(value)
          if (result !== true) {
            errors[field] = result
            return false
          }
        }
      }
      // Handle object rules (parameterized)
      else if (typeof rule === 'object' && rule.type) {
        const ruleFunction = rules[rule.type]
        if (ruleFunction) {
          const result = ruleFunction(rule.value)(value)
          if (result !== true) {
            errors[field] = result
            return false
          }
        }
      }
    }
    
    // If we get here, the field is valid
    delete errors[field]
    return true
  }
  
  // Validate entire form
  const validateForm = (formData, formRules) => {
    let isValid = true
    
    // Clear previous errors
    Object.keys(errors).forEach(key => delete errors[key])
    
    // Validate each field
    for (const field in formRules) {
      const value = formData[field]
      const fieldRules = formRules[field]
      
      if (!validateField(field, value, fieldRules)) {
        isValid = false
      }
    }
    
    return isValid
  }
  
  // Mark field as touched
  const touchField = (field) => {
    touched[field] = true
  }
  
  // Check if field has error
  const hasError = (field) => {
    return !!errors[field]
  }
  
  // Get error message for field
  const getError = (field) => {
    return errors[field] || ''
  }
  
  // Check if field has been touched
  const isTouched = (field) => {
    return !!touched[field]
  }
  
  // Clear all errors
  const clearErrors = () => {
    Object.keys(errors).forEach(key => delete errors[key])
  }
  
  // Clear error for specific field
  const clearError = (field) => {
    delete errors[field]
  }
  
  // Reset validation state
  const resetValidation = () => {
    clearErrors()
    Object.keys(touched).forEach(key => delete touched[key])
  }
  
  return {
    // Reactive properties
    errors,
    touched,
    
    // Methods
    validateField,
    validateForm,
    touchField,
    hasError,
    getError,
    isTouched,
    clearErrors,
    clearError,
    resetValidation
  }
}

// Specific validation composables for common use cases

// Composable for user form validation
export const useUserValidation = () => {
  const { validateForm, ...validation } = useValidation()
  
  const userRules = {
    name: ['required', { type: 'minLength', value: 2 }],
    email: ['required', 'email'],
    password: ['required', { type: 'minLength', value: 8 }],
    confirmPassword: ['required', (value) => {
      const password = document.getElementById('password')?.value || ''
      return value === password || 'Passwords do not match'
    }]
  }
  
  const validateUserForm = (userData) => {
    return validateForm(userData, userRules)
  }
  
  return {
    ...validation,
    validateUserForm,
    userRules
  }
}

// Composable for tenant form validation
export const useTenantValidation = () => {
  const { validateForm, ...validation } = useValidation()
  
  const tenantRules = {
    name: ['required', { type: 'minLength', value: 2 }],
    domain: ['required', 'url'],
    subscription: ['required']
  }
  
  const validateTenantForm = (tenantData) => {
    return validateForm(tenantData, tenantRules)
  }
  
  return {
    ...validation,
    validateTenantForm,
    tenantRules
  }
}

// Composable for booking form validation
export const useBookingValidation = () => {
  const { validateForm, ...validation } = useValidation()
  
  const bookingRules = {
    providerId: ['required'],
    customerId: ['required'],
    serviceId: ['required'],
    date: ['required'],
    time: ['required'],
    duration: ['required', 'numeric', 'positive']
  }
  
  const validateBookingForm = (bookingData) => {
    return validateForm(bookingData, bookingRules)
  }
  
  return {
    ...validation,
    validateBookingForm,
    bookingRules
  }
}

// Export the rules for custom usage
export { rules }