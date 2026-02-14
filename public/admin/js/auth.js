/**
 * Admin login page – password toggle, alerts, submit state
 */
(function () {
    'use strict';

    function init() {
        var toggleBtn = document.getElementById('togglePassword');
        var passwordField = document.getElementById('password');
        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function () {
                var icon = toggleBtn.querySelector('i');
                if (passwordField.getAttribute('type') === 'password') {
                    passwordField.setAttribute('type', 'text');
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                } else {
                    passwordField.setAttribute('type', 'password');
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        }

        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (el) {
                var a = bootstrap.Alert.getOrCreateInstance(el);
                if (a) a.close();
            });
        }, 5000);

        var form = document.getElementById('loginForm');
        if (form) {
            form.addEventListener('submit', function () {
                var btn = document.getElementById('loginBtn');
                var spinner = document.getElementById('loginSpinner');
                var icon = document.getElementById('loginIcon');
                var text = document.getElementById('loginText');
                if (btn) btn.disabled = true;
                if (spinner) spinner.classList.remove('d-none');
                if (icon) icon.classList.add('d-none');
                if (text) text.textContent = 'Signing in...';
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
