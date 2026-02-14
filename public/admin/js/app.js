/**
 * Admin panel base scripts – Wishing Cards
 * Tooltips, mobile menu, alerts, form submit state
 */
(function () {
    'use strict';

    function init() {
        // Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (el) {
            new bootstrap.Tooltip(el);
        });

        // Mobile menu
        var mobileBtn = document.getElementById('mobileMenuBtn');
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (mobileBtn && sidebar && overlay) {
            mobileBtn.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (el) {
                var alert = bootstrap.Alert.getOrCreateInstance(el);
                if (alert) alert.close();
            });
        }, 5000);

        // Form submit: disable button and show loading
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"], #submitBtn');
                if (btn) {
                    btn.disabled = true;
                    var spinner = btn.querySelector('.spinner-border');
                    if (spinner) spinner.classList.remove('d-none');
                    var btnText = btn.querySelector('.btn-text');
                    if (btnText && btnText.dataset.loadingText) {
                        btnText.textContent = btnText.dataset.loadingText;
                    }
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
