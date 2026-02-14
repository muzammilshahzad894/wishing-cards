/**
 * Admin designs list – preview modal (iframe)
 */
(function () {
    'use strict';

    var modal = document.getElementById('previewModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        var iframe = document.getElementById('previewIframe');
        if (btn && btn.dataset.previewUrl && iframe) {
            var titleEl = document.getElementById('previewModalLabel');
            if (titleEl) {
                titleEl.textContent = 'Preview: ' + (btn.dataset.designName || '');
            }
            iframe.src = btn.dataset.previewUrl;
        }
    });

    modal.addEventListener('hidden.bs.modal', function () {
        var iframe = document.getElementById('previewIframe');
        if (iframe) {
            iframe.src = 'about:blank';
        }
    });
})();
