/**
 * Card create page – photo upload, name, toggle, download as image
 * Expects: window.__CARDS_CREATE__ = { namePlaceholder: "Your Name" }
 */
(function () {
    'use strict';

    var config = window.__CARDS_CREATE__ || { namePlaceholder: 'Your Name' };
    var namePlaceholder = config.namePlaceholder;

    var photoPlaceholder = document.getElementById('bdayPhotoPlaceholder');
    var photoImg = document.getElementById('bdayPhotoImg');
    var cardNameEl = document.getElementById('bdayName');
    var nameInput = document.getElementById('nameInput');
    var showNameToggle = document.getElementById('showNameToggle');
    var nameOptionGroup = document.getElementById('nameOptionGroup');
    var choosePhotoBtn = document.getElementById('choosePhotoBtn');
    var photoInput = document.getElementById('photoInput');
    var savePhotoBtn = document.getElementById('savePhotoBtn');
    var cardOutput = document.getElementById('birthdayCardOutput');

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
            photoInput.click();
        });
    }

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            var f = this.files[0];
            if (!f || !f.type.startsWith('image/')) return;
            var r = new FileReader();
            r.onload = function () {
                photoImg.src = r.result;
                photoImg.classList.remove('d-none');
                if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
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
                a.download = 'birthday-card-' + Date.now() + '.png';
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
