/**
 * Admin card builder – template selection, image upload, dimensions, download
 */
(function () {
    'use strict';

    var cardOutput = document.getElementById('cardOutput');
    var cardInner = document.getElementById('cardInner');
    var imagePlaceholder = document.getElementById('imagePlaceholder');
    var cardUserImage = document.getElementById('cardUserImage');
    var cardWishText = document.getElementById('cardWishText');
    var wishTextInput = document.getElementById('wishText');
    var cardWidthInput = document.getElementById('cardWidth');
    var cardHeightInput = document.getElementById('cardHeight');
    var cardPreviewWrapper = document.getElementById('cardPreviewWrapper');
    var imageInput = document.getElementById('imageInput');
    var uploadZone = document.getElementById('uploadZone');
    var imageActions = document.getElementById('imageActions');
    var removeImageBtn = document.getElementById('removeImage');
    var downloadBtn = document.getElementById('downloadCard');

    var selectedImageDataUrl = null;

    function applyDimensions() {
        var w = Math.min(1200, Math.max(300, parseInt(cardWidthInput.value, 10) || 600));
        var h = Math.min(1600, Math.max(400, parseInt(cardHeightInput.value, 10) || 800));
        cardWidthInput.value = w;
        cardHeightInput.value = h;
        var maxPreview = 380;
        var scale = Math.min(1, maxPreview / Math.max(w, h));
        cardOutput.style.width = w + 'px';
        cardOutput.style.height = h + 'px';
        cardPreviewWrapper.style.width = (w * scale) + 'px';
        cardPreviewWrapper.style.height = (h * scale) + 'px';
        cardPreviewWrapper.style.transform = 'scale(' + scale + ')';
    }

    function updateWishText() {
        var text = wishTextInput.value.trim() || 'Happy Birthday!';
        cardWishText.textContent = text;
    }

    function setTemplate(previewClass) {
        cardInner.className = 'card-inner ' + previewClass;
    }

    function handleFile(file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('Please choose an image under 5MB.');
            return;
        }
        var reader = new FileReader();
        reader.onload = function () {
            selectedImageDataUrl = reader.result;
            cardUserImage.src = reader.result;
            cardUserImage.classList.remove('d-none');
            imagePlaceholder.classList.add('d-none');
            uploadZone.classList.add('d-none');
            imageActions.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    if (!cardOutput || !cardInner) return;

    // Template selection
    document.querySelectorAll('.template-option').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.template-option').forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');
            setTemplate(btn.dataset.previewClass);
        });
    });

    // Dimensions
    [cardWidthInput, cardHeightInput].forEach(function (inp) {
        inp.addEventListener('input', applyDimensions);
        inp.addEventListener('change', applyDimensions);
    });
    document.querySelectorAll('[data-width][data-height]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            cardWidthInput.value = btn.dataset.width;
            cardHeightInput.value = btn.dataset.height;
            applyDimensions();
        });
    });

    if (wishTextInput) wishTextInput.addEventListener('input', updateWishText);

    // Image upload
    if (uploadZone) uploadZone.addEventListener('click', function () { imageInput.click(); });
    if (uploadZone) {
        uploadZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        uploadZone.addEventListener('dragleave', function () {
            uploadZone.classList.remove('dragover');
        });
        uploadZone.addEventListener('drop', function (e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            var file = e.dataTransfer && e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) handleFile(file);
        });
    }
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            var file = this.files[0];
            if (file) handleFile(file);
            this.value = '';
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function () {
            selectedImageDataUrl = null;
            cardUserImage.src = '';
            cardUserImage.classList.add('d-none');
            imagePlaceholder.classList.remove('d-none');
            uploadZone.classList.remove('d-none');
            imageActions.classList.add('d-none');
        });
    }

    if (downloadBtn) {
        downloadBtn.addEventListener('click', function () {
            var w = Math.min(1200, Math.max(300, parseInt(cardWidthInput.value, 10) || 600));
            var h = Math.min(1600, Math.max(400, parseInt(cardHeightInput.value, 10) || 800));
            var wrap = cardPreviewWrapper;
            var origW = wrap.style.width;
            var origH = wrap.style.height;
            var origT = wrap.style.transform;
            cardOutput.style.width = w + 'px';
            cardOutput.style.height = h + 'px';
            wrap.style.width = w + 'px';
            wrap.style.height = h + 'px';
            wrap.style.transform = 'none';

            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Preparing...';

            if (typeof html2canvas === 'undefined') {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = '<i class="fas fa-download me-2"></i>Download as image';
                alert('Library not loaded. Please refresh.');
                applyDimensions();
                return;
            }

            html2canvas(cardOutput, {
                scale: 1,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                width: w,
                height: h,
                windowWidth: w,
                windowHeight: h
            }).then(function (canvas) {
                var link = document.createElement('a');
                link.download = 'birthday-card-' + Date.now() + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                wrap.style.width = origW;
                wrap.style.height = origH;
                wrap.style.transform = origT;
                applyDimensions();
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = '<i class="fas fa-download me-2"></i>Download as image';
            }).catch(function (err) {
                console.error(err);
                alert('Download failed. Please try again.');
                applyDimensions();
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = '<i class="fas fa-download me-2"></i>Download as image';
            });
        });
    }

    applyDimensions();
    updateWishText();
})();
