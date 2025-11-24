# Inertia + Vue 3 + Vue Query + Tailwind CSS Setup

This document outlines the setup and usage of the Inertia.js + Vue 3 + Vue Query + Tailwind CSS integration in your Laravel 12 application.

## What's Been Implemented

1. **Inertia.js** - For building server-driven single-page apps
2. **Vue 3** - With Composition API
3. **Vue Query** - For server state management
4. **Tailwind CSS** - For styling (already configured)
5. **Pinia** - For client state management

## Installed Packages

### PHP
- `inertiajs/inertia-laravel` - Laravel adapter for Inertia.js

### Node.js
- `@inertiajs/inertia-vue3` - Vue 3 adapter for Inertia.js
- `@inertiajs/progress` - Progress bar for page loads
- `@tanstack/vue-query` - Server state management
- `@vueuse/core` - Collection of essential Vue composition utilities
- `pinia` - Vue state management
- `@vitejs/plugin-vue` - Vue support for Vite

## File Structure

```
resources/
├── js/
│   ├── app.js              # Main entry point
│   ├── bootstrap.js        # Bootstrap file
│   ├── Layouts/
│   │   └── DefaultLayout.vue
│   ├── Pages/
│   │   └── Users/
│   │       └── Index.vue
│   ├── Components/
│   │   └── UserActions.vue
│   ├── composables/
│   │   └── useUsers.js
│   ├── plugins/
│   │   └── permissions.js
├── css/
│   └── app.css             # Tailwind CSS entry
├── views/
│   └── app.blade.php       # Root Blade template for Inertia
```

## Key Configuration Files

### 1. `resources/views/app.blade.php`
The root template that Inertia uses to render pages.

### 2. `resources/js/app.js`
Main JavaScript entry point that bootstraps the Vue + Inertia application.

### 3. `vite.config.js`
Vite configuration with Vue plugin and path aliases.

### 4. `app/Http/Middleware/HandleInertiaRequests.php`
Middleware that shares data from Laravel to Vue (auth, menu, tenant, etc.).

### 5. `app/Http/Kernel.php`
Registers the HandleInertiaRequests middleware.

### 6. `app/Services/MenuService.php`
Service for generating user-specific menu data.

## How to Create New Pages

1. Create a new Vue component in `resources/js/Pages/`
2. Add a route in `routes/web.php` that renders the page using `Inertia::render()`
3. The page will automatically use the DefaultLayout unless specified otherwise

Example:
```php
// In routes/web.php
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Index', [
        'data' => 'Some data'
    ]);
});
```

```vue
<!-- resources/js/Pages/Dashboard/Index.vue -->
<template>
  <Layout>
    <h1>Dashboard</h1>
    <p>{{ data }}</p>
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/DefaultLayout.vue'
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const data = props.value.data
</script>
```

## Using Vue Query

Vue Query is configured in `resources/js/app.js`. To use it in your components:

```javascript
// resources/js/composables/useUsers.js
import { useQuery } from '@tanstack/vue-query'
import axios from 'axios'

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
```

## Using Permissions

A permissions plugin is available to check user permissions and roles:

```vue
<script setup>
import { usePermissions } from '@/plugins/permissions'

const { can, is } = usePermissions()

// Check if user can do something
if (can('manage-users')) {
  // Show admin features
}

// Check if user has a role
if (is('Super Admin')) {
  // Show super admin features
}
</script>

<template>
  <button v-if="can('manage-users')">Edit User</button>
  <button v-if="is('Super Admin')">Admin Panel</button>
</template>
```

## Testing the Setup

1. Start the development server: `npm run dev`
2. Visit `/test-inertia` to see the sample Users page
3. Visit `/users` to see the UserController in action (you'll need to implement the User model and database)

## Progressive Rollout Plan

1. Start with non-critical pages like dashboards or reports
2. Convert one page at a time while keeping the old Blade versions
3. Test each page thoroughly
4. Monitor performance and user feedback
5. Gradually convert more pages
6. Remove old Blade templates when ready

## Security Best Practices

1. Always enforce permissions on the server-side
2. Client-side permission checks are only for UI/UX
3. Never send sensitive data to the client unless necessary
4. Use Laravel's built-in CSRF protection
5. Sanitize any user-generated content

## Performance Considerations

1. Use code splitting for large applications
2. Cache menu data when possible
3. Use Vue Query's caching features
4. Optimize images and assets
5. Consider SSR for better initial load times