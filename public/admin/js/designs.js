/**
 * Admin designs list – preview modal (iframe)
 */
(function () {
    'use strict';

    function init() {
        var modal = document.getElementById('previewModal');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            var iframe = document.getElementById('previewIframe');
            if (btn && iframe) {
                var url = btn.getAttribute('data-preview-url');
                var name = btn.getAttribute('data-design-name') || '';
                var titleEl = document.getElementById('previewModalLabel');
                if (titleEl) titleEl.textContent = 'Preview: ' + name;
                if (url) iframe.src = url;
            }
        });

        modal.addEventListener('hidden.bs.modal', function () {
            var iframe = document.getElementById('previewIframe');
            if (iframe) iframe.src = 'about:blank';
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
