(function () {
    window.addEventListener('DOMContentLoaded', function () {
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {trigger: 'hover focus'})
        });
    });
})();
