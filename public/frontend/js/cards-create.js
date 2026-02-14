/**
 * Card create page – photo upload, name toggle, download, photo zoom/position
 * Template-safe: works with #card-photo-preview / #card-name-display / #card-canvas-output
 * and fallbacks #bdayPhotoImg / #bdayName / #birthdayCardOutput
 */
(function () {
    'use strict';

    var config = window.__CARDS_CREATE__ || { namePlaceholder: 'Your Name' };
    var namePlaceholder = config.namePlaceholder;

    function byId(id) {
        return id ? document.getElementById(id) : null;
    }

    function query(sel) {
        return sel ? document.querySelector(sel) : null;
    }

    var cardOutput = byId('card-canvas-output') || byId('birthdayCardOutput');
    var photoImg = byId('card-photo-preview') || byId('bdayPhotoImg');
    var photoPlaceholder = query('[data-card-photo-placeholder]') || byId('bdayPhotoPlaceholder');
    var cardNameEl = byId('card-name-display') || byId('bdayName');
    var nameInput = byId('nameInput');
    var showNameToggle = byId('showNameToggle');
    var nameOptionGroup = byId('nameOptionGroup');
    var choosePhotoBtn = byId('choosePhotoBtn');
    var photoInput = byId('photoInput');
    var savePhotoBtn = byId('savePhotoBtn');

    var photoZoomWrap = byId('card-photo-zoom-wrap');
    var photoTransformEl = photoZoomWrap || photoImg;

    var photoState = { scale: 1, x: 0, y: 0 };
    var isDragging = false;
    var dragStart = { x: 0, y: 0, stateX: 0, stateY: 0 };

    function applyPhotoTransform() {
        if (!photoTransformEl) return;
        var s = photoState.scale;
        var x = photoState.x;
        var y = photoState.y;
        photoTransformEl.style.transform = 'scale(' + s + ') translate(' + x + 'px, ' + y + 'px)';
    }

    function onPhotoWheel(e) {
        if (!photoTransformEl || !photoImg) return;
        e.preventDefault();
        var delta = e.deltaY > 0 ? -0.1 : 0.1;
        photoState.scale = Math.max(0.5, Math.min(3, photoState.scale + delta));
        applyPhotoTransform();
    }

    function onPhotoMouseDown(e) {
        if (!photoTransformEl || e.button !== 0) return;
        isDragging = true;
        dragStart.x = e.clientX;
        dragStart.y = e.clientY;
        dragStart.stateX = photoState.x;
        dragStart.stateY = photoState.y;
    }

    function onPhotoMouseMove(e) {
        if (!isDragging) return;
        photoState.x = dragStart.stateX + (e.clientX - dragStart.x);
        photoState.y = dragStart.stateY + (e.clientY - dragStart.y);
        applyPhotoTransform();
    }

    function onPhotoMouseUp() {
        isDragging = false;
    }

    if (photoTransformEl) {
        photoTransformEl.addEventListener('wheel', onPhotoWheel, { passive: false });
        photoTransformEl.addEventListener('mousedown', onPhotoMouseDown);
        document.addEventListener('mousemove', onPhotoMouseMove);
        document.addEventListener('mouseup', onPhotoMouseUp);
    }

    function updateNameVisibility() {
        var show = showNameToggle && showNameToggle.checked;
        if (cardNameEl) {
            if (show) {
                cardNameEl.classList.remove('d-none');
            } else {
                cardNameEl.classList.add('d-none');
            }
        }
        var textInput = nameOptionGroup && nameOptionGroup.querySelector('input[type="text"]');
        if (textInput) {
            textInput.disabled = !show;
        }
    }

    if (showNameToggle) {
        showNameToggle.addEventListener('change', updateNameVisibility);
    }

    if (nameInput && cardNameEl) {
        nameInput.addEventListener('input', function () {
            cardNameEl.textContent = this.value.trim() || namePlaceholder;
        });
    }

    if (choosePhotoBtn) {
        choosePhotoBtn.addEventListener('click', function () {
            if (photoInput) photoInput.click();
        });
    }

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            var f = this.files[0];
            if (!f || !f.type.startsWith('image/')) return;
            var r = new FileReader();
            r.onload = function () {
                if (photoImg) {
                    photoImg.src = r.result;
                    photoImg.classList.remove('d-none');
                }
                if (photoPlaceholder) {
                    photoPlaceholder.classList.add('d-none');
                }
                photoState.scale = 1;
                photoState.x = 0;
                photoState.y = 0;
                applyPhotoTransform();
            };
            r.readAsDataURL(f);
            this.value = '';
        });
    }

    if (savePhotoBtn && cardOutput) {
        savePhotoBtn.addEventListener('click', function () {
            savePhotoBtn.disabled = true;
            savePhotoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            if (typeof html2canvas === 'undefined') {
                savePhotoBtn.disabled = false;
                savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save as image';
                alert('Library not loaded. Please refresh the page.');
                return;
            }
            html2canvas(cardOutput, {
                scale: 1,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null
            }).then(function (canvas) {
                var a = document.createElement('a');
                a.download = 'greeting-card-' + Date.now() + '.png';
                a.href = canvas.toDataURL('image/png');
                a.click();
                savePhotoBtn.disabled = false;
                savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save as image';
            }).catch(function () {
                savePhotoBtn.disabled = false;
                savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save as image';
                alert('Failed to save. Try again.');
            });
        });
    }
})();
