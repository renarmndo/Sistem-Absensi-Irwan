import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Start Alpine after DOM is fully loaded to ensure inline scripts run first
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start());
} else {
    Alpine.start();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'all 0.3s ease-out';
            el.style.opacity = '0';
            el.style.transform = 'translateX(20px)';
            setTimeout(() => el.remove(), 300);
        }, parseInt(el.dataset.toast) || 4000);
    });
});
