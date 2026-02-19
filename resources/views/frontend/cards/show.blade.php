@extends('layouts.app')

@section('title', $template->title)

@section('header-right')
<a href="{{ route('cards.home') }}" class="btn btn-back"><i class="fas fa-arrow-left me-1"></i> All designs</a>
@stop

@section('content')
<div class="container py-4">
    <header class="mb-4">
        <h1 class="create-title">{{ $template->title }}</h1>
        <p class="text-muted mb-0">Add your photo and text, then download your card.</p>
    </header>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body d-flex justify-content-center overflow-auto p-3 bg-light">
                    <canvas id="cardCanvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customize</h5>
                </div>
                <div class="card-body" id="zoneControls">
                    @foreach($template->zones as $index => $zone)
                        @if($zone->type === 'photo')
                            <div class="mb-3 zone-control zone-photo" data-zone-index="{{ $index }}">
                                <label class="form-label">Photo zone</label>
                                <input type="file" class="form-control form-control-sm zone-photo-input" accept="image/*" data-zone-index="{{ $index }}" aria-label="Upload photo for zone">
                            </div>
                        @else
                            <div class="mb-3 zone-control zone-text" data-zone-index="{{ $index }}" data-field-name="{{ $zone->meta['fieldName'] ?? 'text' }}">
                                <label class="form-label">{{ ucfirst($zone->meta['fieldName'] ?? 'Text') }}</label>
                                <input type="text" class="form-control zone-text-input" data-zone-index="{{ $index }}" placeholder="{{ $zone->meta['fieldName'] === 'name' ? 'Your name' : 'Enter text' }}" value="">
                            </div>
                        @endif
                    @endforeach
                    @if($template->zones->isEmpty())
                        <p class="text-muted small mb-0">No editable zones defined for this template.</p>
                    @endif
                </div>
                <div class="card-footer bg-white border-top">
                    <button type="button" class="btn btn-primary w-100" id="downloadCardBtn">
                        <i class="fas fa-download me-2"></i> Download Card
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
#cardCanvas { max-width: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
</style>
@endpush

@push('scripts')
@php
    $cardZones = $template->zones->map(fn ($z) => [
        'type' => $z->type,
        'shape' => $z->shape,
        'x' => (float) $z->x,
        'y' => (float) $z->y,
        'width' => (float) $z->width,
        'height' => (float) $z->height,
        'angle' => (float) ($z->angle ?? 0),
        'scaleX' => (float) ($z->scale_x ?? 1),
        'scaleY' => (float) ($z->scale_y ?? 1),
        'meta' => $z->meta ?? [],
    ])->values()->all();
@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
(function () {
    const canvasEl = document.getElementById('cardCanvas');
    const backgroundUrl = @json($template->background_image_url);
    const zones = @json($cardZones);

    let canvas = null;
    let canvasWidth = 600;
    let canvasHeight = 400;
    const zoneObjects = [];

    function initCanvas() {
        canvas = new fabric.Canvas('cardCanvas', {
            selection: false,
            preserveObjectStacking: true,
        });
    }

    function loadBackground(callback) {
        fabric.Image.fromURL(backgroundUrl, function (img) {
            if (!img) {
                canvas.setDimensions({ width: canvasWidth, height: canvasHeight });
                canvas.setBackgroundColor('#f0f0f0', canvas.renderAll.bind(canvas));
                if (callback) callback();
                return;
            }
            const scale = Math.min(canvasWidth / img.width, canvasHeight / img.height, 1.2);
            canvas.setDimensions({ width: img.width * scale, height: img.height * scale });
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), { scaleX: scale, scaleY: scale });
            if (callback) callback();
        }, { crossOrigin: 'anonymous' });
    }

    function addZoneToCanvas(zone, index) {
        const { type, shape, x, y, width, height, meta, angle, scaleX, scaleY } = zone;
        const angleVal = angle != null ? angle : 0;
        const sx = scaleX != null ? scaleX : 1;
        const sy = scaleY != null ? scaleY : 1;
        let obj;
        if (type === 'photo') {
            obj = createPhotoPlaceholder(shape, x, y, width, height);
        } else {
            const text = meta && meta.fieldName === 'name' ? 'Your name' : '';
            obj = createTextObject(text, x, y, width, height, meta);
        }
        obj.set({ angle: angleVal, scaleX: sx, scaleY: sy });
        obj.data = { zoneIndex: index, type, shape, meta: meta || {} };
        canvas.add(obj);
        zoneObjects[index] = obj;
        canvas.renderAll();
    }

    function createPhotoPlaceholder(shape, x, y, width, height) {
        if (shape === 'circle' || shape === 'oval') {
            return new fabric.Ellipse({
                left: x,
                top: y,
                rx: width / 2,
                ry: height / 2,
                originX: 'left',
                originY: 'top',
                fill: 'rgba(200,200,200,0.5)',
                stroke: '#999',
                strokeWidth: 1,
                selectable: false,
                evented: false,
            });
        }
        return new fabric.Rect({
            left: x,
            top: y,
            width,
            height,
            fill: 'rgba(200,200,200,0.5)',
            stroke: '#999',
            strokeWidth: 1,
            selectable: false,
            evented: false,
        });
    }

    function createTextObject(text, x, y, width, height, meta) {
        const fontSize = (meta && meta.fontSize) ? meta.fontSize : 24;
        const color = (meta && meta.color) ? meta.color : '#333333';
        const txt = new fabric.Text(text || '', {
            left: x,
            top: y,
            fontSize,
            fill: color,
            fontFamily: 'Arial',
            selectable: false,
            evented: false,
        });
        return txt;
    }

    function setPhotoInZone(zoneIndex, file) {
        if (!file || !file.type.match(/^image\//)) return;
        const zone = zones[zoneIndex];
        if (!zone || zone.type !== 'photo') return;
        const reader = new FileReader();
        reader.onload = function (e) {
            fabric.Image.fromURL(e.target.result, function (img) {
                if (!img) return;
                const z = zone;
                const effW = z.width * (z.scaleX != null ? z.scaleX : 1);
                const effH = z.height * (z.scaleY != null ? z.scaleY : 1);
                const scale = Math.min(effW / img.width, effH / img.height);
                img.scale(scale);
                img.set({
                    left: z.x + (effW - img.width * scale) / 2,
                    top: z.y + (effH - img.height * scale) / 2,
                    angle: z.angle != null ? z.angle : 0,
                    selectable: false,
                    evented: false,
                });
                var imgLeft = z.x + (effW - img.width * scale) / 2;
                var imgTop = z.y + (effH - img.height * scale) / 2;
                var clip;
                if (z.shape === 'circle' || z.shape === 'oval') {
                    clip = new fabric.Ellipse({
                        left: z.x - imgLeft,
                        top: z.y - imgTop,
                        rx: z.width / 2,
                        ry: z.height / 2,
                        originX: 'left',
                        originY: 'top',
                    });
                } else {
                    clip = new fabric.Rect({
                        left: z.x - imgLeft,
                        top: z.y - imgTop,
                        width: z.width,
                        height: z.height,
                    });
                }
                img.set({ clipPath: clip });
                const existing = zoneObjects[zoneIndex];
                if (existing) canvas.remove(existing);
                canvas.add(img);
                zoneObjects[zoneIndex] = img;
                canvas.renderAll();
            }, { crossOrigin: 'anonymous' });
        };
        reader.readAsDataURL(file);
    }

    function setTextInZone(zoneIndex, text) {
        const zone = zones[zoneIndex];
        if (!zone || zone.type !== 'text') return;
        const meta = zone.meta || {};
        const existing = zoneObjects[zoneIndex];
        if (existing) canvas.remove(existing);
        const txt = createTextObject(text, zone.x, zone.y, zone.width, zone.height, meta);
        txt.set({
            angle: zone.angle != null ? zone.angle : 0,
            scaleX: zone.scaleX != null ? zone.scaleX : 1,
            scaleY: zone.scaleY != null ? zone.scaleY : 1,
        });
        txt.data = { zoneIndex, type: 'text', shape: zone.shape, meta };
        canvas.add(txt);
        zoneObjects[zoneIndex] = txt;
        canvas.renderAll();
    }

    function downloadCard() {
        const dataUrl = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = dataUrl;
        a.download = 'greeting-card-' + (new Date().toISOString().slice(0, 10)) + '.png';
        a.click();
    }

    initCanvas();
    loadBackground(function () {
        zones.forEach(function (zone, i) {
            addZoneToCanvas(zone, i);
        });
    });

    document.querySelectorAll('.zone-photo-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const index = parseInt(this.getAttribute('data-zone-index'), 10);
            if (this.files && this.files[0]) setPhotoInZone(index, this.files[0]);
        });
    });

    document.querySelectorAll('.zone-text-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const index = parseInt(this.getAttribute('data-zone-index'), 10);
            setTextInZone(index, this.value);
        });
        input.addEventListener('change', function () {
            const index = parseInt(this.getAttribute('data-zone-index'), 10);
            setTextInZone(index, this.value);
        });
    });

    document.getElementById('downloadCardBtn').addEventListener('click', downloadCard);
})();
</script>
@endpush