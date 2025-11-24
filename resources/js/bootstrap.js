import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token to all axios requests
const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}

// Add CSRF token to Inertia requests
import { Inertia } from '@inertiajs/inertia';

Inertia.on('before', (event) => {
    // Add CSRF token to all Inertia requests
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        event.detail.visit.headers['X-CSRF-TOKEN'] = csrfToken.content;
    }
});