@extends('layouts.app')

@section('title', $design->name)

@section('header-right')
<a href="{{ route('cards.home') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> All designs</a>
@stop

@section('content')
<div class="page-title mb-4">
    <h1 class="mb-1">{{ $design->name }}</h1>
    <p class="text-muted mb-0">Add your photo, adjust position and rotation, then save your card.</p>
</div>

<div class="row g-4">
    {{-- Card preview (left) --}}
    <div class="col-lg-7">
        <div class="preview-card-wrap">
            <div class="preview-label">Preview</div>
            <div class="card-canvas-wrap" id="cardCanvasWrap">
                <div class="card-output" id="cardOutput">
                    <div class="card-inner">
                        <img src="{{ asset('storage/' . $design->image) }}" alt="" class="card-design-bg" id="designBg" crossorigin="anonymous" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                        <div class="card-design-fallback d-none" id="designFallback">Design image</div>
                        @php
                            $photoShape = $design->photo_shape ?? 'rectangle';
                        @endphp
                        <div class="card-photo-frame card-photo-frame-{{ $photoShape }}" id="photoFrame" data-x="{{ $design->photo_x_pct }}" data-y="{{ $design->photo_y_pct }}" data-w="{{ $design->photo_width_pct }}" data-h="{{ $design->photo_height_pct }}" data-rot="{{ $design->photo_rotation ?? 0 }}" style="left: {{ $design->photo_x_pct }}%; top: {{ $design->photo_y_pct }}%; width: {{ $design->photo_width_pct }}%; height: {{ $design->photo_height_pct }}%; transform: translate(-50%, -50%) rotate({{ $design->photo_rotation ?? 0 }}deg);">
                            <div class="card-photo-clip" id="photoClip">
                                <div class="card-photo-placeholder" id="photoPlaceholder">
                                    <i class="fas fa-cloud-upload-alt fa-3x card-photo-placeholder-icon"></i>
                                </div>
                                <img src="" alt="" class="card-photo-img d-none" id="photoImg" crossorigin="anonymous" draggable="false">
                            </div>
                        </div>
                        <div class="card-greeting-wrap">
                            <div class="card-greeting" id="cardGreeting">{{ $design->greeting_text }}</div>
                        </div>
                        <div class="card-name-wrap" id="cardNameWrap" style="left: {{ $design->name_x_pct ?? 50 }}%; top: {{ $design->name_y_pct ?? 88 }}%; width: {{ $design->name_width_pct ?? 80 }}%; transform: translate(-50%, -50%); text-align: {{ $design->name_align ?? 'center' }};">
                            <div class="card-name" id="cardName" data-placeholder="{{ e($design->name_placeholder) }}" style="font-size: {{ $design->name_font_size ?? 18 }}px;">{{ $design->name_placeholder }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Controls (right) --}}
    <div class="col-lg-5">
        <div class="controls-card">
            <h6 class="controls-title">Customize your card</h6>
            <div class="mb-4">
                <button type="button" class="btn btn-primary btn-choose-photo w-100 py-3" id="choosePhotoBtn">
                    <i class="fas fa-image me-2"></i>Choose Photo
                </button>
                <input type="file" id="photoInput" accept="image/*" class="d-none">
            </div>
            <div class="d-none photo-toolbar" id="photoControls">
                <p class="small text-muted mb-2">Drag on the photo to move it; use the mouse wheel on the photo to zoom in or out. Use buttons to rotate.</p>
                <div class="btn-group w-100 mb-2" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="undoBtn" title="Undo"><i class="fas fa-undo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="redoBtn" title="Redo"><i class="fas fa-redo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomInBtn" title="Zoom in"><i class="fas fa-search-plus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomOutBtn" title="Zoom out"><i class="fas fa-search-minus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="rotateLeftBtn" title="Rotate left"><i class="fas fa-rotate-left"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="rotateRightBtn" title="Rotate right"><i class="fas fa-rotate-right"></i></button>
                </div>
                <div class="btn-group w-100 mb-3" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="moveUpBtn"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="moveLeftBtn"><i class="fas fa-arrow-left"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="moveRightBtn"><i class="fas fa-arrow-right"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="moveDownBtn"><i class="fas fa-arrow-down"></i></button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Name on card</label>
                <input type="text" class="form-control form-control-lg rounded-3" id="nameInput" placeholder="{{ $design->name_placeholder }}" value="">
            </div>
            <button type="button" class="btn btn-success btn-save w-100 py-3" id="savePhotoBtn">
                <i class="fas fa-download me-2"></i>Save Photo
            </button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .page-title h1 { font-size: 1.75rem; font-weight: 700; color: var(--text); }
    .preview-card-wrap { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 1.5rem; }
    .preview-label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 1rem; }
    .card-canvas-wrap { display: flex; justify-content: center; align-items: center; min-height: 420px; }
    .card-output { width: 400px; height: 520px; min-width: 400px; min-height: 520px; position: relative; background: #f5f0e8; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
    .card-inner { position: relative; width: 100%; height: 100%; }
    .card-design-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: fill; display: block; }
    .card-design-fallback { position: absolute; inset: 0; background: linear-gradient(135deg, #e8e0d5 0%, #d4c4b0 100%); display: flex; align-items: center; justify-content: center; color: #8a7f72; font-size: 1.1rem; }
    .card-photo-frame { position: absolute; overflow: hidden; background: transparent; cursor: grab; user-select: none; -webkit-user-select: none; }
    .card-photo-frame:active { cursor: grabbing; }
    .card-photo-frame.card-photo-frame-rounded { border-radius: 16px; }
    .card-photo-frame.card-photo-frame-circle { border-radius: 50%; }
    .card-photo-frame.card-photo-frame-ellipse { border-radius: 50%; }
    .card-photo-clip { position: absolute; left: 0; top: 0; width: 100%; height: 100%; overflow: hidden; }
    .card-photo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .card-photo-placeholder-icon { opacity: 0.25; color: #666; }
    .card-photo-img { position: absolute; width: 200%; height: 200%; max-width: none; max-height: none; object-fit: cover; transform-origin: center center; left: 50%; top: 50%; margin-left: -100%; margin-top: -100%; box-sizing: content-box; pointer-events: none; }
    .card-greeting-wrap { position: absolute; bottom: 18%; left: 0; right: 0; text-align: center; padding: 0 1rem; pointer-events: none; }
    .card-greeting { font-size: 1.25rem; font-weight: 700; color: #333; }
    .card-name-wrap { position: absolute; padding: 0 0.5rem; pointer-events: none; box-sizing: border-box; }
    .card-name { font-weight: 600; color: #444; line-height: 1.3; word-wrap: break-word; }
    .controls-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 1.75rem; }
    .controls-title { font-weight: 600; margin-bottom: 1.25rem; color: var(--text); }
    .btn-choose-photo { border-radius: 12px; font-weight: 600; }
    .btn-save { border-radius: 12px; font-weight: 600; }
    @media (max-width: 991px) {
        .card-output { width: 320px; height: 416px; min-width: 320px; min-height: 416px; }
        .card-canvas-wrap { min-height: 380px; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function() {
    const CARD_W = 400, CARD_H = 520;
    const photoFrame = document.getElementById('photoFrame');
    const photoImg = document.getElementById('photoImg');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const cardGreeting = document.getElementById('cardGreeting');
    const cardName = document.getElementById('cardName');
    const nameInput = document.getElementById('nameInput');
    const choosePhotoBtn = document.getElementById('choosePhotoBtn');
    const photoInput = document.getElementById('photoInput');
    const photoControls = document.getElementById('photoControls');
    const savePhotoBtn = document.getElementById('savePhotoBtn');
    const cardOutput = document.getElementById('cardOutput');

    let scale = 1, posX = 0, posY = 0, angle = 0;
    let history = [], historyStep = -1;

    function getTransform() {
        return 'translate(' + posX + 'px,' + posY + 'px) scale(' + scale + ') rotate(' + angle + 'deg)';
    }
    function saveState() {
        const state = { scale, posX, posY, angle };
        if (historyStep < history.length - 1) history = history.slice(0, historyStep + 1);
        history.push(state);
        if (history.length > 30) history.shift();
        historyStep = history.length - 1;
    }
    function applyState(s) {
        scale = s.scale; posX = s.posX; posY = s.posY; angle = s.angle;
        photoImg.style.transform = getTransform();
    }
    function applyPhotoTransform() {
        photoImg.style.transform = getTransform();
    }

    nameInput.addEventListener('input', function() {
        cardName.textContent = this.value.trim() || (cardName.dataset.placeholder || 'Any Name Here');
    });

    choosePhotoBtn.addEventListener('click', function() { photoInput.click(); });
    photoFrame.addEventListener('click', function(e) {
        if (photoPlaceholder.classList.contains('d-none')) return;
        e.preventDefault();
        photoInput.click();
    });
    photoInput.addEventListener('change', function() {
        const f = this.files[0];
        if (!f || !f.type.startsWith('image/')) return;
        const r = new FileReader();
        r.onload = function() {
            photoImg.src = r.result;
            photoImg.classList.remove('d-none');
            photoPlaceholder.classList.add('d-none');
            photoControls.classList.remove('d-none');
            scale = 1; posX = 0; posY = 0; angle = 0;
            saveState();
            applyPhotoTransform();
        };
        r.readAsDataURL(f);
        this.value = '';
    });

    // Pan (move) photo by mouse/touch drag on photo frame
    let isDragging = false, startX, startY, startPosX, startPosY;
    photoFrame.addEventListener('mousedown', function(e) {
        if (photoPlaceholder.classList.contains('d-none')) {
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startPosX = posX;
            startPosY = posY;
        }
    });
    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        posX = startPosX + (e.clientX - startX);
        posY = startPosY + (e.clientY - startY);
        applyPhotoTransform();
    });
    document.addEventListener('mouseup', function() {
        if (isDragging) saveState();
        isDragging = false;
    });
    photoFrame.addEventListener('touchstart', function(e) {
        if (photoPlaceholder.classList.contains('d-none') && e.touches.length === 1) {
            isDragging = true;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            startPosX = posX;
            startPosY = posY;
        }
    }, { passive: true });
    document.addEventListener('touchmove', function(e) {
        if (!isDragging || e.touches.length !== 1) return;
        posX = startPosX + (e.touches[0].clientX - startX);
        posY = startPosY + (e.touches[0].clientY - startY);
        applyPhotoTransform();
    }, { passive: true });
    document.addEventListener('touchend', function() {
        if (isDragging) saveState();
        isDragging = false;
    });

    // Mouse wheel zoom on photo frame
    photoFrame.addEventListener('wheel', function(e) {
        if (photoPlaceholder.classList.contains('d-none')) {
            e.preventDefault();
            saveState();
            const delta = e.deltaY > 0 ? -0.15 : 0.15;
            scale = Math.max(0.3, Math.min(3, scale + delta));
            applyPhotoTransform();
        }
    }, { passive: false });

    document.getElementById('undoBtn').addEventListener('click', function() {
        if (historyStep > 0) { historyStep--; applyState(history[historyStep]); }
    });
    document.getElementById('redoBtn').addEventListener('click', function() {
        if (historyStep < history.length - 1) { historyStep++; applyState(history[historyStep]); }
    });
    document.getElementById('zoomInBtn').addEventListener('click', function() {
        saveState();
        scale = Math.min(3, scale + 0.2);
        applyPhotoTransform();
    });
    document.getElementById('zoomOutBtn').addEventListener('click', function() {
        saveState();
        scale = Math.max(0.3, scale - 0.2);
        applyPhotoTransform();
    });
    document.getElementById('rotateLeftBtn').addEventListener('click', function() {
        saveState();
        angle -= 15;
        applyPhotoTransform();
    });
    document.getElementById('rotateRightBtn').addEventListener('click', function() {
        saveState();
        angle += 15;
        applyPhotoTransform();
    });
    const M = 10;
    document.getElementById('moveUpBtn').addEventListener('click', function() { saveState(); posY += M; applyPhotoTransform(); });
    document.getElementById('moveDownBtn').addEventListener('click', function() { saveState(); posY -= M; applyPhotoTransform(); });
    document.getElementById('moveLeftBtn').addEventListener('click', function() { saveState(); posX += M; applyPhotoTransform(); });
    document.getElementById('moveRightBtn').addEventListener('click', function() { saveState(); posX -= M; applyPhotoTransform(); });

    savePhotoBtn.addEventListener('click', function() {
        savePhotoBtn.disabled = true;
        savePhotoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        var opt = {
            scale: 1,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#f5f0e8',
            width: CARD_W,
            height: CARD_H,
            onclone: function(clonedDoc) {
                var clone = clonedDoc.getElementById('cardOutput');
                if (!clone) return;
                clone.style.width = CARD_W + 'px';
                clone.style.height = CARD_H + 'px';
                var frame = clonedDoc.getElementById('photoFrame');
                if (frame && frame.dataset.x !== undefined) {
                    var x = parseFloat(frame.dataset.x) || 50, y = parseFloat(frame.dataset.y) || 38;
                    var w = parseFloat(frame.dataset.w) || 55, h = parseFloat(frame.dataset.h) || 55, rot = parseFloat(frame.dataset.rot) || 0;
                    var leftPx = (CARD_W * x / 100) - (CARD_W * w / 100) / 2;
                    var topPx = (CARD_H * y / 100) - (CARD_H * h / 100) / 2;
                    frame.style.left = leftPx + 'px';
                    frame.style.top = topPx + 'px';
                    frame.style.width = (CARD_W * w / 100) + 'px';
                    frame.style.height = (CARD_H * h / 100) + 'px';
                    frame.style.transform = 'translate(0, 0) rotate(' + rot + 'deg)';
                }
            }
        };
        html2canvas(cardOutput, opt).then(function(canvas) {
            const a = document.createElement('a');
            a.download = 'birthday-card-' + Date.now() + '.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
            savePhotoBtn.disabled = false;
            savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save Photo';
        }).catch(function() {
            savePhotoBtn.disabled = false;
            savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save Photo';
            alert('Failed to save. Try again.');
        });
    });
})();
</script>
@endsection
