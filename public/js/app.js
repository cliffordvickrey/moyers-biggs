// noinspection ES6ConvertVarToLetConst

(function () {
    var nullListener = function (e) {
        e.preventDefault();
        return false;
    };

    window.addEventListener('DOMContentLoaded', function () {
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (tooltipTriggerEl) {
            tooltipTriggerEl.addEventListener('click', nullListener);

            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function (popoverTriggerEl) {
            popoverTriggerEl.addEventListener('click', nullListener);

            return new bootstrap.Popover(popoverTriggerEl, {trigger: 'hover focus'});
        });

        var timeZoneSelect = document.getElementById('time-zone');

        if (null === timeZoneSelect) {
            return;
        }

        timeZoneSelect.addEventListener('change', function () {
            document.getElementById('time-zone-form').submit();
        });
    });
})();
