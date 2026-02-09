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
        @php
            $px = old('photo_x_pct', $design?->photo_x_pct ?? 50);
            $py = old('photo_y_pct', $design?->photo_y_pct ?? 38);
            $pw = old('photo_width_pct', $design?->photo_width_pct ?? 55);
            $ph = old('photo_height_pct', $design?->photo_height_pct ?? 55);
            $pleft = old('photo_left_pct', $design?->photo_left_pct ?? ($px - $pw/2));
            $ptop = old('photo_top_pct', $design?->photo_top_pct ?? ($py - $ph/2));
            $pright = old('photo_right_pct', $design?->photo_right_pct ?? ($px + $pw/2));
            $pbottom = old('photo_bottom_pct', $design?->photo_bottom_pct ?? ($py + $ph/2));
        @endphp
        <p class="small text-muted mb-2">Choose a frame shape, then <strong>drag on the image</strong> to draw the selection area. You can also move the box, drag edges/corners to resize, or use the top handle to rotate.</p>
        <div class="mb-2">
            <label class="form-label small">Frame shape</label>
            <select name="photo_shape" id="photo_shape" class="form-select form-select-sm">
                <option value="rectangle" {{ old('photo_shape', $design?->photo_shape ?? 'rectangle') == 'rectangle' ? 'selected' : '' }}>Rectangle</option>
                <option value="rounded" {{ old('photo_shape', $design?->photo_shape ?? 'rectangle') == 'rounded' ? 'selected' : '' }}>Rounded rectangle</option>
                <option value="circle" {{ old('photo_shape', $design?->photo_shape ?? 'rectangle') == 'circle' ? 'selected' : '' }}>Circle</option>
                <option value="ellipse" {{ old('photo_shape', $design?->photo_shape ?? 'rectangle') == 'ellipse' ? 'selected' : '' }}>Ellipse</option>
            </select>
        </div>
        <input type="hidden" name="photo_left_pct" id="photo_left_pct" value="{{ $pleft }}">
        <input type="hidden" name="photo_top_pct" id="photo_top_pct" value="{{ $ptop }}">
        <input type="hidden" name="photo_right_pct" id="photo_right_pct" value="{{ $pright }}">
        <input type="hidden" name="photo_bottom_pct" id="photo_bottom_pct" value="{{ $pbottom }}">
        <input type="hidden" name="photo_x_pct" id="photo_x_pct" value="{{ $px }}">
        <input type="hidden" name="photo_y_pct" id="photo_y_pct" value="{{ $py }}">
        <input type="hidden" name="photo_width_pct" id="photo_width_pct" value="{{ $pw }}">
        <input type="hidden" name="photo_height_pct" id="photo_height_pct" value="{{ $ph }}">
        <input type="hidden" name="photo_rotation" id="photo_rotation" value="{{ old('photo_rotation', $design?->photo_rotation ?? 0) }}">
        @if($design)
        <div class="frame-editor-wrap" id="photoFrameEditor" style="max-width: 320px; aspect-ratio: 400/520; position: relative; background: #eee; border-radius: 8px; overflow: visible; touch-action: none;">
            <img src="{{ asset('storage/' . $design->image) }}" alt="" class="w-100 h-100" style="object-fit: fill; display: block; border-radius: 8px; pointer-events: none;">
            <div class="photo-frame-box frame-shape-{{ old('photo_shape', $design?->photo_shape ?? 'rectangle') }}" id="photoFrameBox">
                <span class="frame-rotate-handle" id="photoRotateHandle" title="Drag to rotate">⟳</span>
                <span class="frame-edge-handle frame-edge-t" data-edge="top" title="Drag to adjust top"></span>
                <span class="frame-edge-handle frame-edge-r" data-edge="right" title="Drag to adjust right"></span>
                <span class="frame-edge-handle frame-edge-b" data-edge="bottom" title="Drag to adjust bottom"></span>
                <span class="frame-edge-handle frame-edge-l" data-edge="left" title="Drag to adjust left"></span>
                <span class="frame-corner-handle frame-corner-tl" data-corner="tl" title="Drag corner"></span>
                <span class="frame-corner-handle frame-corner-tr" data-corner="tr" title="Drag corner"></span>
                <span class="frame-corner-handle frame-corner-br" data-corner="br" title="Drag corner"></span>
                <span class="frame-corner-handle frame-corner-bl" data-corner="bl" title="Drag corner"></span>
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
.photo-frame-box { transform-origin: center center; box-sizing: border-box; }
.photo-frame-box.frame-shape-rounded { border-radius: 16px; }
.photo-frame-box.frame-shape-circle { border-radius: 50%; }
.photo-frame-box.frame-shape-ellipse { border-radius: 50%; }
.frame-rotate-handle { position: absolute; top: -28px; left: 50%; transform: translateX(-50%); width: 24px; height: 24px; background: #667eea; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: grab; line-height: 1; z-index: 2; }
.frame-rotate-handle:active { cursor: grabbing; }
.frame-edge-handle { position: absolute; background: #667eea; border: 2px solid #fff; z-index: 1; }
.frame-edge-handle.frame-edge-t { left: 10%; right: 10%; top: -4px; height: 10px; cursor: n-resize; }
.frame-edge-handle.frame-edge-r { right: -4px; top: 10%; bottom: 10%; width: 10px; cursor: e-resize; }
.frame-edge-handle.frame-edge-b { left: 10%; right: 10%; bottom: -4px; height: 10px; cursor: s-resize; }
.frame-edge-handle.frame-edge-l { left: -4px; top: 10%; bottom: 10%; width: 10px; cursor: w-resize; }
.frame-corner-handle { position: absolute; width: 14px; height: 14px; background: #667eea; border: 2px solid #fff; border-radius: 2px; z-index: 1; }
.frame-corner-handle.frame-corner-tl { left: -6px; top: -6px; cursor: nwse-resize; }
.frame-corner-handle.frame-corner-tr { right: -6px; top: -6px; cursor: nesw-resize; }
.frame-corner-handle.frame-corner-br { right: -6px; bottom: -6px; cursor: nwse-resize; }
.frame-corner-handle.frame-corner-bl { left: -6px; bottom: -6px; cursor: nesw-resize; }
.name-frame-box { cursor: move; min-width: 40px; min-height: 24px; }
</style>
<script>
(function() {
    var wrap = document.getElementById('photoFrameEditor');
    if (!wrap) return;
    var box = document.getElementById('photoFrameBox');
    var rotHandle = document.getElementById('photoRotateHandle');
    var MIN = 5;

    function getVal(id) { return parseFloat(document.getElementById(id).value) || 0; }
    function setVal(id, v) { document.getElementById(id).value = String(v); }

    function getEdges() {
        return { left: getVal('photo_left_pct'), top: getVal('photo_top_pct'), right: getVal('photo_right_pct'), bottom: getVal('photo_bottom_pct') };
    }
    function setEdges(left, top, right, bottom) {
        left = Math.max(0, Math.min(100, left));
        top = Math.max(0, Math.min(100, top));
        right = Math.max(0, Math.min(100, right));
        bottom = Math.max(0, Math.min(100, bottom));
        if (right - left < MIN) right = left + MIN;
        if (bottom - top < MIN) bottom = top + MIN;
        setVal('photo_left_pct', left); setVal('photo_top_pct', top); setVal('photo_right_pct', right); setVal('photo_bottom_pct', bottom);
        setVal('photo_x_pct', (left + right) / 2); setVal('photo_y_pct', (top + bottom) / 2);
        setVal('photo_width_pct', right - left); setVal('photo_height_pct', bottom - top);
    }

    function applyPhotoBox() {
        var e = getEdges(), r = getVal('photo_rotation');
        box.style.left = e.left + '%'; box.style.top = e.top + '%';
        box.style.width = (e.right - e.left) + '%'; box.style.height = (e.bottom - e.top) + '%';
        box.style.transform = 'rotate(' + r + 'deg)';
        var shape = (document.getElementById('photo_shape') || {}).value || 'rectangle';
        box.className = 'photo-frame-box frame-shape-' + shape;
    }
    if (document.getElementById('photo_shape')) {
        document.getElementById('photo_shape').addEventListener('change', applyPhotoBox);
    }
    applyPhotoBox();

    var dragging = null, startX, startY, startEdges, startRot, startAngle;
    function pxToPctX(px) { return (px / wrap.offsetWidth) * 100; }
    function pxToPctY(px) { return (px / wrap.offsetHeight) * 100; }

    box.addEventListener('mousedown', function(e) {
        if (e.target.closest('.frame-rotate-handle, .frame-edge-handle, .frame-corner-handle')) return;
        e.preventDefault();
        dragging = 'move';
        startX = e.clientX; startY = e.clientY;
        startEdges = getEdges();
    });
    rotHandle.addEventListener('mousedown', function(e) {
        e.preventDefault(); e.stopPropagation();
        dragging = 'rotate';
        startX = e.clientX; startY = e.clientY;
        startRot = getVal('photo_rotation');
        var rect = wrap.getBoundingClientRect(), cx = rect.left + rect.width * getVal('photo_x_pct')/100, cy = rect.top + rect.height * getVal('photo_y_pct')/100;
        startAngle = Math.atan2(startY - cy, startX - cx);
    });
    [].forEach.call(document.querySelectorAll('.frame-edge-handle'), function(el) {
        el.addEventListener('mousedown', function(e) {
            e.preventDefault(); e.stopPropagation();
            dragging = 'edge:' + el.getAttribute('data-edge');
            startX = e.clientX; startY = e.clientY;
            startEdges = getEdges();
        });
    });
    [].forEach.call(document.querySelectorAll('.frame-corner-handle'), function(el) {
        el.addEventListener('mousedown', function(e) {
            e.preventDefault(); e.stopPropagation();
            dragging = 'corner:' + el.getAttribute('data-corner');
            startX = e.clientX; startY = e.clientY;
            startEdges = getEdges();
        });
    });

    document.addEventListener('mousemove', function(e) {
        if (!dragging) return;
        if (dragging === 'nameMove') return;
        var dx = pxToPctX(e.clientX - startX), dy = pxToPctY(e.clientY - startY);
        var L = startEdges && startEdges.left, T = startEdges && startEdges.top, R = startEdges && startEdges.right, B = startEdges && startEdges.bottom;
        if (dragging === 'move') {
            setEdges(L + dx, T + dy, R + dx, B + dy);
        } else if (dragging === 'rotate') {
            var rect = wrap.getBoundingClientRect(), cx = rect.left + rect.width * getVal('photo_x_pct')/100, cy = rect.top + rect.height * getVal('photo_y_pct')/100;
            var curAngle = Math.atan2(e.clientY - cy, e.clientX - cx);
            setVal('photo_rotation', startRot + (curAngle - startAngle) * 180 / Math.PI);
        } else if (dragging === 'edge:top') {
            var newTop = Math.max(0, Math.min(B - MIN, T + dy));
            setEdges(L, newTop, R, B);
        } else if (dragging === 'edge:bottom') {
            var newBottom = Math.max(T + MIN, Math.min(100, B + dy));
            setEdges(L, T, R, newBottom);
        } else if (dragging === 'edge:left') {
            var newLeft = Math.max(0, Math.min(R - MIN, L + dx));
            setEdges(newLeft, T, R, B);
        } else if (dragging === 'edge:right') {
            var newRight = Math.max(L + MIN, Math.min(100, R + dx));
            setEdges(L, T, newRight, B);
        } else if (dragging === 'corner:tl') {
            setEdges(Math.max(0, Math.min(R - MIN, L + dx)), Math.max(0, Math.min(B - MIN, T + dy)), R, B);
        } else if (dragging === 'corner:tr') {
            setEdges(L, Math.max(0, Math.min(B - MIN, T + dy)), Math.max(L + MIN, Math.min(100, R + dx)), B);
        } else if (dragging === 'corner:br') {
            setEdges(L, T, Math.max(L + MIN, Math.min(100, R + dx)), Math.max(T + MIN, Math.min(100, B + dy)));
        } else if (dragging === 'corner:bl') {
            setEdges(Math.max(0, Math.min(R - MIN, L + dx)), T, R, Math.max(T + MIN, Math.min(100, B + dy)));
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
