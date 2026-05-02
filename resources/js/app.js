// resources/js/app.js
import './bootstrap';

// Initialize tooltips, dropdowns, etc.
document.addEventListener('alpine:init', () => {
    // Global error handler for AJAX
    window.addEventListener('unhandledrejection', event => {
        console.error('Unhandled promise rejection:', event.reason);
        // Show a toast notification
        Alpine.store('toasts').push({
            message: 'An error occurred. Please try again.',
            type: 'error'
        });
    });
});

// Toast notification system
document.addEventListener('alpine:init', () => {
    Alpine.store('toasts', {
        items: [],
        add(message, type = 'info') {
            const id = Date.now();
            this.items.push({ id, message, type });
            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        remove(id) {
            this.items = this.items.filter(item => item.id !== id);
        }
    });
});