/**
 * Admin Utilities - Zero Dependencies
 * Shared functions for all admin management views
 * @version 1.0.0
 */

// Live Search with Debounce
const AdminSearch = {
    debounceTimer: null,

    /**
     * Initialize live search on a table or list
     * @param {string} inputId - ID of search input
     * @param {string} rowSelector - CSS selector for rows to search
     * @param {array} searchFields - Optional array of selectors for specific fields to search
     */
    init(inputId, rowSelector, searchFields = []) {
        const input = document.getElementById(inputId);
        const rows = document.querySelectorAll(rowSelector);

        if (!input || !rows.length) {
            console.warn(`AdminSearch: Could not find input "${inputId}" or rows "${rowSelector}"`);
            return;
        }

        input.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.filter(e.target.value, rows, searchFields);
            }, 300);
        });

        // Initial count
        this.updateCount(rows);
    },

    /**
     * Filter rows based on query
     */
    filter(query, rows, fields) {
        query = query.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            let text;

            if (fields.length) {
                // Search specific fields
                text = fields.map(selector => {
                    const element = row.querySelector(selector);
                    return element ? element.textContent : '';
                }).join(' ');
            } else {
                // Search entire row
                text = row.textContent;
            }

            const matches = text.toLowerCase().includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        this.updateCount(rows, visibleCount);
    },

    /**
     * Update results counter
     */
    updateCount(rows, visible = null) {
        const total = rows.length;
        const showing = visible !== null ? visible : total;
        const counter = document.getElementById('results-count');

        if (counter) {
            counter.innerHTML = `Showing <strong>${showing}</strong> of <strong>${total}</strong> result${total !== 1 ? 's' : ''}`;
        }
    }
};

// Toast Notification System
const AdminToast = {
    /**
     * Show a toast notification
     * @param {string} message - Message to display
     * @param {string} type - Type: success, error, warning, info
     * @param {number} duration - Duration in ms (default 3000)
     */
    show(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${colors[type] || colors.info} transform transition-all duration-300 z-50 flex items-center gap-2`;
        toast.innerHTML = `<span class="text-lg font-bold">${icons[type] || icons.info}</span><span>${message}</span>`;

        document.body.appendChild(toast);

        // Fade out
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
        }, duration);

        // Remove from DOM
        setTimeout(() => toast.remove(), duration + 300);
    },

    success(message) {
        this.show(message, 'success');
    },

    error(message) {
        this.show(message, 'error');
    },

    warning(message) {
        this.show(message, 'warning');
    },

    info(message) {
        this.show(message, 'info');
    }
};

// Modal System
const AdminModal = {
    current: null,

    /**
     * Show a modal
     * @param {string} modalId - ID of modal to show
     */
    show(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.warn(`AdminModal: Could not find modal "${modalId}"`);
            return;
        }

        modal.classList.remove('hidden');
        this.current = modalId;
        document.body.style.overflow = 'hidden';

        // Focus first input or button
        const firstFocusable = modal.querySelector('input, button, textarea, select');
        if (firstFocusable) {
            setTimeout(() => firstFocusable.focus(), 100);
        }
    },

    /**
     * Hide a modal
     * @param {string} modalId - Optional ID, defaults to current modal
     */
    hide(modalId = null) {
        const id = modalId || this.current;
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.add('hidden');
        this.current = null;
        document.body.style.overflow = '';
    }
};

/**
 * Show confirmation dialog before deleting
 * @param {string} formId - ID of form to submit
 * @param {string} itemName - Name of item being deleted
 */
function confirmDelete(formId, itemName) {
    const modalHtml = `
        <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirm Deletion</h3>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    Are you sure you want to delete <strong class="text-gray-900 dark:text-white">${itemName}</strong>?
                </p>
                <p class="text-sm text-red-600 dark:text-red-400 mb-6">
                    ⚠️ This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition">
                        Cancel
                    </button>
                    <button onclick="document.getElementById('${formId}').submit()" 
                            class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if present
    const existing = document.getElementById('delete-modal');
    if (existing) existing.remove();

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    AdminModal.show('delete-modal');

    // Close on ESC key
    const escapeHandler = (e) => {
        if (e.key === 'Escape') {
            closeDeleteModal();
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);
}

/**
 * Close delete modal
 */
function closeDeleteModal() {
    AdminModal.hide('delete-modal');
    const modal = document.getElementById('delete-modal');
    if (modal) {
        setTimeout(() => modal.remove(), 300);
    }
}

// Form Validation
const AdminValidation = {
    /**
     * Validate a form
     * @param {string} formId - ID of form to validate
     * @returns {boolean} - True if valid
     */
    validate(formId) {
        const form = document.getElementById(formId);
        if (!form) {
            console.warn(`AdminValidation: Could not find form "${formId}"`);
            return false;
        }

        let isValid = true;
        const fields = form.querySelectorAll('[required]');

        fields.forEach(field => {
            const value = field.value.trim();

            if (!value) {
                this.showError(field, 'This field is required');
                isValid = false;
            } else if (field.type === 'email' && !this.isValidEmail(value)) {
                this.showError(field, 'Please enter a valid email address');
                isValid = false;
            } else {
                this.clearError(field);
            }
        });

        return isValid;
    },

    /**
     * Show error message for field
     */
    showError(field, message) {
        field.classList.add('border-red-500', 'dark:border-red-400');
        field.classList.remove('border-gray-300', 'dark:border-gray-600');

        const parent = field.closest('.form-group, .mb-4, .mb-6');
        if (!parent) return;

        let error = parent.querySelector('.error-message');
        if (!error) {
            error = document.createElement('p');
            error.className = 'error-message text-red-500 dark:text-red-400 text-sm mt-1';
            field.parentElement.appendChild(error);
        }

        error.textContent = message;
        error.classList.remove('hidden');
    },

    /**
     * Clear error for field
     */
    clearError(field) {
        field.classList.remove('border-red-500', 'dark:border-red-400');
        field.classList.add('border-gray-300', 'dark:border-gray-600');

        const parent = field.closest('.form-group, .mb-4, .mb-6');
        if (!parent) return;

        const error = parent.querySelector('.error-message');
        if (error) {
            error.classList.add('hidden');
        }
    },

    /**
     * Validate email format
     */
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
};

// Loading State Management
const AdminLoading = {
    /**
     * Show loading state on button
     * @param {HTMLElement} button - Button element
     */
    start(button) {
        if (!button) return;

        button.disabled = true;
        button.dataset.originalText = button.innerHTML;

        button.innerHTML = `
            <svg class="animate-spin h-5 w-5 inline-block mr-2" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
    },

    /**
     * Stop loading state
     * @param {HTMLElement} button - Button element
     */
    stop(button) {
        if (!button) return;

        button.disabled = false;
        if (button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
        }
    }
};

// Export to window for global access
window.AdminSearch = AdminSearch;
window.AdminToast = AdminToast;
window.AdminModal = AdminModal;
window.AdminValidation = AdminValidation;
window.AdminLoading = AdminLoading;
window.confirmDelete = confirmDelete;
window.closeDeleteModal = closeDeleteModal;

// Show success/error toasts from session flash messages
document.addEventListener('DOMContentLoaded', function () {
    // Check for Laravel flash messages
    const successMessage = document.querySelector('meta[name="flash-success"]');
    const errorMessage = document.querySelector('meta[name="flash-error"]');

    if (successMessage) {
        AdminToast.success(successMessage.content);
    }

    if (errorMessage) {
        AdminToast.error(errorMessage.content);
    }
});

console.log('✓ Admin Utils loaded successfully');
