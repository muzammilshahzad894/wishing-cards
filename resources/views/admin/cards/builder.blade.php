@extends('admin.layout')

@section('title', 'Create Birthday Card')

@section('content')
<div class="card builder-card">
    <div class="card-header">
        <i class="fas fa-birthday-cake"></i>
        Create Birthday Card
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Controls -->
            <div class="col-lg-4">
                <div class="builder-controls">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">1. Choose template</label>
                        <div class="template-grid">
                            @foreach($templates as $tpl)
                            <button type="button" class="template-option {{ $loop->first ? 'active' : '' }}" data-template="{{ $tpl['id'] }}" data-preview-class="{{ $tpl['preview_class'] }}" title="{{ $tpl['name'] }}">
                                <span class="template-preview {{ $tpl['preview_class'] }}"></span>
                                <span class="template-name">{{ $tpl['name'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">2. Upload your image</label>
                        <div class="upload-zone border rounded-3 p-4 text-center" id="uploadZone">
                            <input type="file" id="imageInput" accept="image/*" class="d-none">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-1 small text-muted">Click or drag image here</p>
                            <p class="small text-muted">PNG, JPG up to 5MB</p>
                        </div>
                        <div class="mt-2 d-flex gap-2 align-items-center d-none" id="imageActions">
                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeImage"><i class="fas fa-times me-1"></i>Remove</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="wishText">3. Your wish message</label>
                        <textarea class="form-control" id="wishText" rows="3" placeholder="e.g. Wishing you a wonderful birthday filled with joy!">Happy Birthday! Wishing you a day filled with laughter and joy.</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">4. Card dimensions (px)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small text-muted">Width</label>
                                <input type="number" class="form-control dimension-input" id="cardWidth" value="600" min="300" max="1200" step="10">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Height</label>
                                <input type="number" class="form-control dimension-input" id="cardHeight" value="800" min="400" max="1600" step="10">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-width="600" data-height="800">6×8</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-width="800" data-height="600">8×6</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-width="1080" data-height="1080">Square</button>
                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-primary btn-lg w-100" id="downloadCard">
                            <i class="fas fa-download me-2"></i>Download as image
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="col-lg-8">
                <div class="preview-wrapper bg-light rounded-3 p-4 d-flex justify-content-center align-items-start overflow-auto" style="min-height: 520px;">
                    <div class="card-preview-wrapper" id="cardPreviewWrapper">
                        <div class="card-output" id="cardOutput">
                            <div class="card-inner template-elegant" id="cardInner">
                                <div class="card-image-area">
                                    <div class="card-image-placeholder" id="imagePlaceholder">
                                        <i class="fas fa-image"></i>
                                        <span>Your photo will appear here</span>
                                    </div>
                                    <img src="" alt="" class="card-user-image d-none" id="cardUserImage" crossorigin="anonymous">
                                </div>
                                <div class="card-wish-text" id="cardWishText">Happy Birthday! Wishing you a day filled with laughter and joy.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .builder-controls .form-control:focus, .builder-controls .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15); }
    .template-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .template-option { display: flex; flex-direction: column; align-items: center; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; background: #fff; cursor: pointer; transition: all 0.2s; }
    .template-option:hover { border-color: var(--primary-color); background: #f8f9ff; }
    .template-option.active { border-color: var(--primary-color); background: rgba(102, 126, 234, 0.08); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2); }
    .template-preview { width: 100%; height: 56px; border-radius: 8px; margin-bottom: 0.5rem; }
    .template-name { font-size: 0.8rem; font-weight: 600; color: #2d3748; text-align: center; }
    .upload-zone { cursor: pointer; transition: all 0.2s; background: #fafafa; }
    .upload-zone:hover { background: #f0f4ff; border-color: var(--primary-color) !important; }
    .upload-zone.dragover { background: #e8eeff; border-color: var(--primary-color) !important; }
    .dimension-input { max-width: 100%; }

    /* Card output - this is what we export */
    .card-preview-wrapper { transform-origin: top center; }
    .card-output { box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 4px; overflow: hidden; background: #fff; }
    .card-inner { width: 100%; height: 100%; display: flex; flex-direction: column; padding: 0; }
    .card-image-area { position: relative; width: 100%; flex: 1; min-height: 45%; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .card-image-placeholder { width: 100%; height: 100%; min-height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%); color: #718096; font-size: 0.9rem; }
    .card-image-placeholder i { font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.7; }
    .card-user-image { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .card-wish-text { padding: 1.25rem 1.5rem; text-align: center; font-size: 1rem; line-height: 1.5; }

    /* Template: Elegant Gold */
    .card-inner.template-elegant { background: linear-gradient(180deg, #fefce8 0%, #fef9c3 50%, #fef08a 100%); }
    .card-inner.template-elegant .card-image-area { border-bottom: 4px solid #b45309; }
    .card-inner.template-elegant .card-image-placeholder { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
    .card-inner.template-elegant .card-wish-text { font-family: Georgia, serif; color: #78350f; font-size: 1.05rem; border-top: 2px solid #b45309; }

    /* Template: Modern Gradient */
    .card-inner.template-modern { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-inner.template-modern .card-image-area { border-bottom: none; box-shadow: inset 0 -1px 0 rgba(255,255,255,0.2); }
    .card-inner.template-modern .card-image-placeholder { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); }
    .card-inner.template-modern .card-wish-text { color: #fff; font-weight: 500; text-shadow: 0 1px 2px rgba(0,0,0,0.2); }

    /* Template: Playful Party */
    .card-inner.template-playful { background: linear-gradient(180deg, #fce7f3 0%, #fbcfe8 30%, #f9a8d4 100%); }
    .card-inner.template-playful .card-image-area { border-radius: 0 0 24px 24px; border: 4px solid #ec4899; }
    .card-inner.template-playful .card-image-placeholder { background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); border-radius: 0 0 20px 20px; }
    .card-inner.template-playful .card-wish-text { color: #831843; font-weight: 600; font-size: 1.1rem; }

    /* Template: Minimal Clean */
    .card-inner.template-minimal { background: #ffffff; border: 1px solid #e5e7eb; }
    .card-inner.template-minimal .card-image-area { border-bottom: 1px solid #e5e7eb; }
    .card-inner.template-minimal .card-image-placeholder { background: #f9fafb; color: #6b7280; }
    .card-inner.template-minimal .card-wish-text { color: #374151; font-size: 0.95rem; letter-spacing: 0.02em; }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function() {
    const cardOutput = document.getElementById('cardOutput');
    const cardInner = document.getElementById('cardInner');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const cardUserImage = document.getElementById('cardUserImage');
    const cardWishText = document.getElementById('cardWishText');
    const wishTextInput = document.getElementById('wishText');
    const cardWidthInput = document.getElementById('cardWidth');
    const cardHeightInput = document.getElementById('cardHeight');
    const cardPreviewWrapper = document.getElementById('cardPreviewWrapper');
    const imageInput = document.getElementById('imageInput');
    const uploadZone = document.getElementById('uploadZone');
    const imageActions = document.getElementById('imageActions');
    const removeImageBtn = document.getElementById('removeImage');
    const downloadBtn = document.getElementById('downloadCard');

    let selectedImageDataUrl = null;

    function applyDimensions() {
        const w = Math.min(1200, Math.max(300, parseInt(cardWidthInput.value, 10) || 600));
        const h = Math.min(1600, Math.max(400, parseInt(cardHeightInput.value, 10) || 800));
        cardWidthInput.value = w;
        cardHeightInput.value = h;
        const maxPreview = 380;
        const scale = Math.min(1, maxPreview / Math.max(w, h));
        cardOutput.style.width = w + 'px';
        cardOutput.style.height = h + 'px';
        cardPreviewWrapper.style.width = (w * scale) + 'px';
        cardPreviewWrapper.style.height = (h * scale) + 'px';
        cardPreviewWrapper.style.transform = 'scale(' + scale + ')';
    }

    function updateWishText() {
        const text = wishTextInput.value.trim() || 'Happy Birthday!';
        cardWishText.textContent = text;
    }

    function setTemplate(previewClass) {
        cardInner.className = 'card-inner ' + previewClass;
    }

    // Template selection
    document.querySelectorAll('.template-option').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.template-option').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            setTemplate(btn.dataset.previewClass);
        });
    });

    // Dimensions
    [cardWidthInput, cardHeightInput].forEach(function(inp) {
        inp.addEventListener('input', applyDimensions);
        inp.addEventListener('change', applyDimensions);
    });
    document.querySelectorAll('[data-width][data-height]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            cardWidthInput.value = btn.dataset.width;
            cardHeightInput.value = btn.dataset.height;
            applyDimensions();
        });
    });

    // Wish text
    wishTextInput.addEventListener('input', updateWishText);

    // Image upload
    uploadZone.addEventListener('click', function() { imageInput.click(); });
    uploadZone.addEventListener('dragover', function(e) { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', function() { uploadZone.classList.remove('dragover'); });
    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const file = e.dataTransfer && e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) handleFile(file);
    });
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) handleFile(file);
        this.value = '';
    });

    function handleFile(file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('Please choose an image under 5MB.');
            return;
        }
        const reader = new FileReader();
        reader.onload = function() {
            selectedImageDataUrl = reader.result;
            cardUserImage.src = reader.result;
            cardUserImage.classList.remove('d-none');
            imagePlaceholder.classList.add('d-none');
            uploadZone.classList.add('d-none');
            imageActions.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    removeImageBtn.addEventListener('click', function() {
        selectedImageDataUrl = null;
        cardUserImage.src = '';
        cardUserImage.classList.add('d-none');
        imagePlaceholder.classList.remove('d-none');
        uploadZone.classList.remove('d-none');
        imageActions.classList.add('d-none');
    });

    // Download
    downloadBtn.addEventListener('click', function() {
        const w = Math.min(1200, Math.max(300, parseInt(cardWidthInput.value, 10) || 600));
        const h = Math.min(1600, Math.max(400, parseInt(cardHeightInput.value, 10) || 800));
        const wrap = cardPreviewWrapper;
        const origW = wrap.style.width, origH = wrap.style.height, origT = wrap.style.transform;
        cardOutput.style.width = w + 'px';
        cardOutput.style.height = h + 'px';
        wrap.style.width = w + 'px';
        wrap.style.height = h + 'px';
        wrap.style.transform = 'none';

        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Preparing...';

        html2canvas(cardOutput, {
            scale: 1,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            width: w,
            height: h,
            windowWidth: w,
            windowHeight: h
        }).then(function(canvas) {
            const link = document.createElement('a');
            link.download = 'birthday-card-' + Date.now() + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            wrap.style.width = origW;
            wrap.style.height = origH;
            wrap.style.transform = origT;
            applyDimensions();
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<i class="fas fa-download me-2"></i>Download as image';
        }).catch(function(err) {
            console.error(err);
            alert('Download failed. Please try again.');
            applyDimensions();
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<i class="fas fa-download me-2"></i>Download as image';
        });
    });

    applyDimensions();
    updateWishText();
})();
</script>
@endsection
