import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { VueQueryPlugin, QueryClient } from '@tanstack/vue-query'
import { createPinia } from 'pinia'
import '../css/app.css'

// Create the Vue Query client
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 1000 * 10,
      refetchOnWindowFocus: false,
      retry: 1
    }
  }
})

// Create the Inertia app
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.use(VueQueryPlugin, { queryClient })
    app.use(createPinia())
    app.component('InertiaLink', Link)
    app.component('InertiaHead', Head)
    app.mount(el)
  },
})

InertiaProgress.init({ delay: 250 })
