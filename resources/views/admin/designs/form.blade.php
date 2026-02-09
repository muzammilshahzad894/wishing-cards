@php
    $design = $design ?? null;
@endphp
<div class="mb-3">
    <label class="form-label">Design name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $design?->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Design image <span class="text-danger">*</span></label>
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" {{ $design ? '' : 'required' }}>
    @if($design)
        <p class="small text-muted mt-1">Leave empty to keep current image.</p>
        <button type="button" class="btn btn-link p-0 text-decoration-none mt-2 d-block border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" title="Click to view full size">
            <img src="{{ asset('storage/' . $design->image) }}" alt="" class="img-thumbnail design-edit-thumb" style="max-height: 140px; cursor: pointer;">
        </button>
    @endif
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Greeting text (e.g. Happy Birthday)</label>
    <input type="text" name="greeting_text" class="form-control @error('greeting_text') is-invalid @enderror" value="{{ old('greeting_text', $design?->greeting_text ?? 'Happy Birthday') }}">
    @error('greeting_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Name placeholder (e.g. Any Name Here)</label>
    <input type="text" name="name_placeholder" class="form-control @error('name_placeholder') is-invalid @enderror" value="{{ old('name_placeholder', $design?->name_placeholder ?? 'Any Name Here') }}">
    @error('name_placeholder')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="card bg-light border-0 mb-3">
    <div class="card-body">
        <h6 class="card-title mb-2"><i class="fas fa-crop-alt me-2"></i>Photo frame</h6>
        <p class="small text-muted mb-2">Drag the box to move, drag the corner to resize, drag the top handle to rotate. User's photo will appear inside this area on the frontend.</p>
        <input type="hidden" name="photo_x_pct" id="photo_x_pct" value="{{ old('photo_x_pct', $design?->photo_x_pct ?? 50) }}">
        <input type="hidden" name="photo_y_pct" id="photo_y_pct" value="{{ old('photo_y_pct', $design?->photo_y_pct ?? 38) }}">
        <input type="hidden" name="photo_width_pct" id="photo_width_pct" value="{{ old('photo_width_pct', $design?->photo_width_pct ?? 55) }}">
        <input type="hidden" name="photo_height_pct" id="photo_height_pct" value="{{ old('photo_height_pct', $design?->photo_height_pct ?? 55) }}">
        <input type="hidden" name="photo_rotation" id="photo_rotation" value="{{ old('photo_rotation', $design?->photo_rotation ?? 0) }}">
        @if($design)
        <div class="frame-editor-wrap" id="photoFrameEditor" style="max-width: 320px; aspect-ratio: 400/520; position: relative; background: #eee; border-radius: 8px; overflow: visible; touch-action: none;">
            <img src="{{ asset('storage/' . $design->image) }}" alt="" class="w-100 h-100" style="object-fit: fill; display: block; border-radius: 8px; pointer-events: none;">
            <div class="photo-frame-box" id="photoFrameBox">
                <span class="frame-rotate-handle" id="photoRotateHandle" title="Drag to rotate">⟳</span>
                <span class="frame-resize-handle" id="photoResizeHandle" title="Drag to resize"></span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card bg-light border-0 mb-3">
    <div class="card-body">
        <h6 class="card-title mb-2"><i class="fas fa-font me-2"></i>Name area</h6>
        <p class="small text-muted mb-2">Set where the recipient's name will appear. Drag the box to position it.</p>
        <input type="hidden" name="name_x_pct" id="name_x_pct" value="{{ old('name_x_pct', $design?->name_x_pct ?? 50) }}">
        <input type="hidden" name="name_y_pct" id="name_y_pct" value="{{ old('name_y_pct', $design?->name_y_pct ?? 88) }}">
        <input type="hidden" name="name_width_pct" id="name_width_pct" value="{{ old('name_width_pct', $design?->name_width_pct ?? 80) }}">
        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label small">Align</label>
                <select name="name_align" id="name_align" class="form-select form-select-sm">
                    <option value="left" {{ old('name_align', $design?->name_align ?? 'center') == 'left' ? 'selected' : '' }}>Left</option>
                    <option value="center" {{ old('name_align', $design?->name_align ?? 'center') == 'center' ? 'selected' : '' }}>Center</option>
                    <option value="right" {{ old('name_align', $design?->name_align ?? 'center') == 'right' ? 'selected' : '' }}>Right</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label small">Font size (px)</label>
                <input type="number" name="name_font_size" id="name_font_size" class="form-control form-control-sm" value="{{ old('name_font_size', $design?->name_font_size ?? 18) }}" min="10" max="72">
            </div>
        </div>
        @if($design)
        <div class="frame-editor-wrap" id="nameFrameEditor" style="max-width: 320px; aspect-ratio: 400/520; position: relative; background: #eee; border-radius: 8px; overflow: visible; touch-action: none;">
            <img src="{{ asset('storage/' . $design->image) }}" alt="" class="w-100 h-100" style="object-fit: fill; display: block; border-radius: 8px; pointer-events: none;">
            <div class="name-frame-box" id="nameFrameBox"></div>
        </div>
        @endif
    </div>
</div>

<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $design?->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Active (show on frontend for users)</label>
    </div>
</div>

@if($design)
<style>
.frame-editor-wrap { user-select: none; }
.photo-frame-box, .name-frame-box { position: absolute; border: 2px dashed rgba(102, 126, 234, 0.9); background: rgba(102, 126, 234, 0.12); cursor: move; }
.photo-frame-box { transform-origin: center center; }
.frame-rotate-handle { position: absolute; top: -28px; left: 50%; transform: translateX(-50%); width: 24px; height: 24px; background: #667eea; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: grab; line-height: 1; }
.frame-rotate-handle:active { cursor: grabbing; }
.frame-resize-handle { position: absolute; bottom: -6px; right: -6px; width: 16px; height: 16px; background: #667eea; border: 2px solid #fff; border-radius: 2px; cursor: nwse-resize; }
.name-frame-box { cursor: move; min-width: 40px; min-height: 24px; }
</style>
<script>
(function() {
    var wrap = document.getElementById('photoFrameEditor');
    if (!wrap) return;
    var box = document.getElementById('photoFrameBox');
    var rotHandle = document.getElementById('photoRotateHandle');
    var resHandle = document.getElementById('photoResizeHandle');
    var inpX = document.getElementById('photo_x_pct');
    var inpY = document.getElementById('photo_y_pct');
    var inpW = document.getElementById('photo_width_pct');
    var inpH = document.getElementById('photo_height_pct');
    var inpRot = document.getElementById('photo_rotation');

    function getVal(id) { return parseFloat(document.getElementById(id).value) || 0; }
    function setVal(id, v) { document.getElementById(id).value = String(v); }

    function applyPhotoBox() {
        var x = getVal('photo_x_pct'), y = getVal('photo_y_pct'), w = getVal('photo_width_pct'), h = getVal('photo_height_pct'), r = getVal('photo_rotation');
        box.style.left = x + '%'; box.style.top = y + '%'; box.style.width = w + '%'; box.style.height = h + '%';
        box.style.transform = 'translate(-50%, -50%) rotate(' + r + 'deg)';
    }
    applyPhotoBox();

    var dragging = null, startX, startY, startV1, startV2;
    function pxToPctX(px) { return (px / wrap.offsetWidth) * 100; }
    function pxToPctY(px) { return (px / wrap.offsetHeight) * 100; }

    box.addEventListener('mousedown', function(e) {
        if (e.target === rotHandle || e.target === resHandle) return;
        e.preventDefault();
        dragging = 'move';
        startX = e.clientX; startY = e.clientY;
        startV1 = getVal('photo_x_pct'); startV2 = getVal('photo_y_pct');
    });
    rotHandle.addEventListener('mousedown', function(e) {
        e.preventDefault(); e.stopPropagation();
        dragging = 'rotate';
        startX = e.clientX; startY = e.clientY;
        startV1 = getVal('photo_rotation');
        var rect = wrap.getBoundingClientRect();
        startV2 = Math.atan2(startY - (rect.top + rect.height * getVal('photo_y_pct')/100), startX - (rect.left + rect.width * getVal('photo_x_pct')/100));
    });
    resHandle.addEventListener('mousedown', function(e) {
        e.preventDefault(); e.stopPropagation();
        dragging = 'resize';
        startX = e.clientX; startY = e.clientY;
        startV1 = getVal('photo_width_pct'); startV2 = getVal('photo_height_pct');
    });

    document.addEventListener('mousemove', function(e) {
        if (!dragging) return;
        if (dragging === 'nameMove') return;
        var dx = e.clientX - startX, dy = e.clientY - startY;
        if (dragging === 'move') {
            setVal('photo_x_pct', Math.max(0, Math.min(100, startV1 + pxToPctX(dx))));
            setVal('photo_y_pct', Math.max(0, Math.min(100, startV2 + pxToPctY(dy))));
        } else if (dragging === 'rotate') {
            var rect = wrap.getBoundingClientRect();
            var cx = rect.left + rect.width * getVal('photo_x_pct')/100;
            var cy = rect.top + rect.height * getVal('photo_y_pct')/100;
            var curAngle = Math.atan2(e.clientY - cy, e.clientX - cx);
            var deg = startV1 + (curAngle - startV2) * 180 / Math.PI;
            setVal('photo_rotation', deg);
        } else if (dragging === 'resize') {
            var dw = pxToPctX(dx), dh = pxToPctY(dy);
            setVal('photo_width_pct', Math.max(10, Math.min(100, startV1 + dw)));
            setVal('photo_height_pct', Math.max(10, Math.min(100, startV2 + dh)));
        }
        applyPhotoBox();
    });
    document.addEventListener('mouseup', function() { dragging = null; });

    // Name frame
    var nameWrap = document.getElementById('nameFrameEditor');
    var nameBox = document.getElementById('nameFrameBox');
    if (nameWrap && nameBox) {
        var nameInpX = document.getElementById('name_x_pct');
        var nameInpY = document.getElementById('name_y_pct');
        var nameInpW = document.getElementById('name_width_pct');
        function applyNameBox() {
            var x = parseFloat(nameInpX.value) || 50, y = parseFloat(nameInpY.value) || 88, w = parseFloat(nameInpW.value) || 80;
            nameBox.style.left = x + '%'; nameBox.style.top = y + '%'; nameBox.style.width = w + '%';
            nameBox.style.transform = 'translate(-50%, -50%)';
            nameBox.style.height = '12%';
        }
        applyNameBox();
        nameBox.addEventListener('mousedown', function(e) {
            e.preventDefault();
            dragging = 'nameMove';
            startX = e.clientX; startY = e.clientY;
            startV1 = parseFloat(nameInpX.value) || 50;
            startV2 = parseFloat(nameInpY.value) || 88;
        });
        document.addEventListener('mousemove', function(e) {
            if (dragging !== 'nameMove') return;
            var dx = e.clientX - startX, dy = e.clientY - startY;
            nameInpX.value = Math.max(0, Math.min(100, startV1 + (dx / nameWrap.offsetWidth) * 100));
            nameInpY.value = Math.max(0, Math.min(100, startV2 + (dy / nameWrap.offsetHeight) * 100));
            applyNameBox();
        });
    }
})();
</script>
@endif

{{-- Image preview modal (only on edit page; create page doesn't have image yet) --}}
@if($design)
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="imagePreviewModalLabel">Design preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="{{ asset('storage/' . $design->image) }}" alt="{{ $design->name }}" class="img-fluid rounded shadow-sm" style="max-height: 75vh; width: auto;">
                <p class="text-muted small mt-2 mb-0">{{ $design->name }}</p>
            </div>
        </div>
    </div>
</div>
@endif
