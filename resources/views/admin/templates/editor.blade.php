@extends('admin.layout')

@section('title', 'Edit zones: ' . $template->title)

@section('content')
<div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0">Edit zones: {{ $template->title }}</h5>
    <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary btn-sm" id="btnDrawPhotoRect" title="Draw photo zone (rectangle)">
                <i class="fas fa-square me-1"></i> Draw Photo (Rect)
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btnDrawPhotoCircle" title="Draw photo zone (circle)">
                <i class="fas fa-circle me-1"></i> Draw Photo (Circle)
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddTextName" title="Add text zone (name)">
                <i class="fas fa-font me-1"></i> Text (Name)
            </button>
        </div>
        <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeleteSelected" title="Delete selected zone">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>
        <button type="button" class="btn btn-success btn-sm" id="saveZonesBtn">
            <i class="fas fa-save me-1"></i> Save zones
        </button>
    </div>
</div>
<p class="text-muted mb-3">
    <strong>Draw mode:</strong> Click "Draw Photo (Rect)" or "Draw Photo (Circle)", then drag on the canvas to create a zone.
    Or add a fixed text zone. Select any zone to move, resize (corners + sides), or rotate (handle above). Hold <kbd>Shift</kbd> while resizing a corner for uniform scale. Press <kbd>Delete</kbd> or click "Delete Selected" to remove.
</p>
<div class="card">
    <div class="card-body d-flex justify-content-center overflow-auto p-3">
        <canvas id="templateEditorCanvas"></canvas>
    </div>
</div>
<div id="zonesSaveStatus" class="mt-2 small" aria-live="polite"></div>

@push('styles')
<style>
#templateEditorCanvas { border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0,0,0,0.1); cursor: default; }
#templateEditorCanvas.draw-mode { cursor: crosshair; }
.editor-draw-active { background-color: rgba(13, 110, 253, 0.2) !important; }
</style>
@endpush

@push('scripts')
@php
    $editorZones = $template->zones->map(fn ($z) => [
        'type' => $z->type,
        'shape' => $z->shape,
        'x' => (float) $z->x,
        'y' => (float) $z->y,
        'width' => (float) $z->width,
        'height' => (float) $z->height,
        'angle' => (float) ($z->angle ?? 0),
        'scaleX' => (float) ($z->scale_x ?? 1),
        'scaleY' => (float) ($z->scale_y ?? 1),
        'meta' => $z->meta,
    ])->values()->all();
@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
(function () {
    "use strict";

    var canvasEl = document.getElementById("templateEditorCanvas");
    var backgroundUrl = @json($template->background_image_url);
    var existingZones = @json($editorZones);
    var saveZonesUrl = @json(route('admin.templates.zones.store', $template));
    var csrfToken = @json(csrf_token());

    var canvas = null;
    var canvasWidth = 600;
    var canvasHeight = 400;

    // Draw mode: "rect" | "circle" | null
    var drawMode = null;
    var drawStart = null;
    var drawPreview = null;

    var ZONE_FILL = "rgba(255, 255, 255, 0.15)";
    var ZONE_STROKE = "#0d6efd";
    var ZONE_STROKE_WIDTH = 2;
    var ZONE_STROKE_DASH = [8, 4];

    function initCanvas() {
        canvas = new fabric.Canvas("templateEditorCanvas", {
            selection: true,
            preserveObjectStacking: true,
            targetFindTolerance: 4,
        });
        // Canva-like controls: 8 resize handles + 1 rotation handle
        fabric.Object.prototype.set({
            transparentCorners: false,
            cornerColor: ZONE_STROKE,
            cornerStrokeColor: ZONE_STROKE,
            borderColor: ZONE_STROKE,
            cornerStyle: "circle",
            cornerSize: 10,
            hasRotatingPoint: true,
            lockRotation: false,
        });
        canvas.on("selection:created", function () { updateDeleteButton(); });
        canvas.on("selection:updated", function () { updateDeleteButton(); });
        canvas.on("selection:cleared", function () { updateDeleteButton(); });
    }

    function loadBackground(callback) {
        fabric.Image.fromURL(backgroundUrl, function (img) {
            if (!img) {
                canvas.setDimensions({ width: canvasWidth, height: canvasHeight });
                canvas.setBackgroundColor("#f0f0f0", canvas.renderAll.bind(canvas));
                if (callback) callback();
                return;
            }
            var imgW = img.get ? img.get("width") : img.width;
            var imgH = img.get ? img.get("height") : img.height;
            if (!imgW || !imgH) {
                canvas.setDimensions({ width: canvasWidth, height: canvasHeight });
                canvas.setBackgroundColor("#f0f0f0", canvas.renderAll.bind(canvas));
                if (callback) callback();
                return;
            }
            var scale = Math.min(canvasWidth / imgW, canvasHeight / imgH, 1.2);
            var w = imgW * scale;
            var h = imgH * scale;
            canvas.setDimensions({ width: w, height: h });
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), { scaleX: scale, scaleY: scale });
            if (callback) callback();
        }, { crossOrigin: "anonymous" });
    }

    function isZoneObject(obj) {
        return obj && obj.data && (obj.data.type === "photo" || obj.data.type === "text");
    }

    function createShapeOnly(shape, x, y, w, h) {
        var opts = {
            left: x,
            top: y,
            fill: ZONE_FILL,
            stroke: ZONE_STROKE,
            strokeWidth: ZONE_STROKE_WIDTH,
            strokeDashArray: ZONE_STROKE_DASH,
            selectable: false,
            evented: false,
            originX: "left",
            originY: "top",
        };
        if (shape === "rect") {
            return new fabric.Rect({ width: w, height: h, ...opts });
        }
        if (shape === "circle") {
            var r = Math.min(w, h) / 2;
            return new fabric.Circle({
                radius: r,
                left: x,
                top: y,
                originX: "left",
                originY: "top",
                fill: ZONE_FILL,
                stroke: ZONE_STROKE,
                strokeWidth: ZONE_STROKE_WIDTH,
                strokeDashArray: ZONE_STROKE_DASH,
                selectable: false,
                evented: false,
            });
        }
        if (shape === "oval") {
            return new fabric.Ellipse({
                rx: w / 2,
                ry: h / 2,
                left: x,
                top: y,
                originX: "left",
                originY: "top",
                fill: ZONE_FILL,
                stroke: ZONE_STROKE,
                strokeWidth: ZONE_STROKE_WIDTH,
                strokeDashArray: ZONE_STROKE_DASH,
                selectable: false,
                evented: false,
            });
        }
        return new fabric.Rect({ width: w, height: h, ...opts });
    }

    function createZoneGroup(type, shape, x, y, w, h, meta, placeholderText, optTransform) {
        var shapeObj = createShapeOnly(shape, 0, 0, w, h);
        shapeObj.set("selectable", false);
        shapeObj.set("evented", false);

        var text = new fabric.Text(placeholderText || "", {
            left: w / 2,
            top: h / 2,
            originX: "center",
            originY: "center",
            fontSize: Math.min(14, w / 10, h / 3),
            fill: "#666",
            fontFamily: "Arial",
            selectable: false,
            evented: false,
        });

        var groupOpts = {
            left: x,
            top: y,
            hasControls: true,
            hasBorders: true,
            hasRotatingPoint: true,
            lockRotation: false,
            lockScalingX: false,
            lockScalingY: false,
            lockUniScaling: false,
            transparentCorners: false,
            cornerStyle: "circle",
            cornerSize: 10,
            selectable: true,
            evented: true,
            subTargetCheck: true,
            interactive: true,
            data: { type: type, shape: shape, meta: meta || {} },
        };
        if (shape === "circle") {
            groupOpts.lockUniScaling = true;
            groupOpts.lockScalingFlip = true;
        }
        var group = new fabric.Group([shapeObj, text], groupOpts);
        if (optTransform) {
            if (optTransform.angle != null) group.set("angle", optTransform.angle);
            if (optTransform.scaleX != null) group.set("scaleX", optTransform.scaleX);
            if (optTransform.scaleY != null) group.set("scaleY", optTransform.scaleY);
        }
        return group;
    }

    function addRectangleZone(type, x, y, width, height, meta, optTransform) {
        type = type || "photo";
        meta = meta || {};
        var placeholder = type === "photo" ? "Drop Photo Here" : "Name Here";
        var group = createZoneGroup(type, "rect", x, y, width, height, meta, placeholder, optTransform);
        canvas.add(group);
        canvas.setActiveObject(group);
        canvas.renderAll();
        return group;
    }

    function addCircleZone(type, x, y, width, height, meta, optTransform) {
        type = type || "photo";
        meta = meta || {};
        var size = Math.max(Math.min(width, height), 40);
        var placeholder = type === "photo" ? "Drop Photo Here" : "Name Here";
        var group = createZoneGroup(type, "circle", x, y, size, size, meta, placeholder, optTransform);
        canvas.add(group);
        canvas.setActiveObject(group);
        canvas.renderAll();
        return group;
    }

    function addOvalZone(type, x, y, width, height, meta, optTransform) {
        type = type || "photo";
        meta = meta || {};
        var placeholder = type === "photo" ? "Drop Photo Here" : "Name Here";
        var group = createZoneGroup(type, "oval", x, y, width, height, meta, placeholder, optTransform);
        canvas.add(group);
        canvas.setActiveObject(group);
        canvas.renderAll();
        return group;
    }

    function enableDrawMode(shape) {
        drawMode = shape;
        drawStart = null;
        if (drawPreview) {
            canvas.remove(drawPreview);
            drawPreview = null;
            canvas.renderAll();
        }
        canvas.selection = !drawMode;
        canvasEl.classList.toggle("draw-mode", !!drawMode);
        document.getElementById("btnDrawPhotoRect").classList.toggle("editor-draw-active", drawMode === "rect");
        document.getElementById("btnDrawPhotoCircle").classList.toggle("editor-draw-active", drawMode === "circle");
    }

    function disableDrawMode() {
        drawMode = null;
        drawStart = null;
        if (drawPreview) {
            canvas.remove(drawPreview);
            drawPreview = null;
        }
        canvas.selection = true;
        canvasEl.classList.remove("draw-mode");
        document.getElementById("btnDrawPhotoRect").classList.remove("editor-draw-active");
        document.getElementById("btnDrawPhotoCircle").classList.remove("editor-draw-active");
        canvas.renderAll();
    }

    function getGroupZoneData(group) {
        if (!group || !group.data) return null;
        var type = group.data.type;
        var shape = group.data.shape || "rect";
        group.setCoords();
        var left = group.left != null ? group.left : group.get("left");
        var top = group.top != null ? group.top : group.get("top");
        var baseW = group.width != null ? group.width : group.get("width");
        var baseH = group.height != null ? group.height : group.get("height");
        var scaleX = group.scaleX != null ? group.scaleX : 1;
        var scaleY = group.scaleY != null ? group.scaleY : 1;
        var angle = group.angle != null ? group.angle : group.get("angle") || 0;
        return {
            type: type,
            shape: shape,
            x: Math.round(left * 100) / 100,
            y: Math.round(top * 100) / 100,
            width: Math.round(baseW * 100) / 100,
            height: Math.round(baseH * 100) / 100,
            scaleX: Math.round(scaleX * 1000) / 1000,
            scaleY: Math.round(scaleY * 1000) / 1000,
            angle: Math.round(angle * 100) / 100,
            meta: group.data.meta || null,
        };
    }

    function exportZones() {
        var objects = canvas.getObjects();
        var out = [];
        for (var i = 0; i < objects.length; i++) {
            var obj = objects[i];
            if (obj === drawPreview) continue;
            var data = getGroupZoneData(obj);
            if (data && data.width >= 1 && data.height >= 1) out.push(data);
        }
        return out;
    }

    function saveZonesToLaravel() {
        var zones = exportZones();
        var statusEl = document.getElementById("zonesSaveStatus");
        statusEl.textContent = "Saving…";
        statusEl.className = "mt-2 small text-muted";

        fetch(saveZonesUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({ zones: zones }),
        })
            .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
            .then(function (ref) {
                var ok = ref.ok;
                var data = ref.data;
                if (ok && data.success) {
                    statusEl.textContent = "Zones saved successfully (" + (data.count || zones.length) + " zone(s)).";
                    statusEl.className = "mt-2 small text-success";
                } else {
                    statusEl.textContent = data.message || "Failed to save zones.";
                    statusEl.className = "mt-2 small text-danger";
                }
            })
            .catch(function () {
                statusEl.textContent = "Network error. Could not save zones.";
                statusEl.className = "mt-2 small text-danger";
            });
    }

    function loadExistingZones() {
        var transform = { angle: 0, scaleX: 1, scaleY: 1 };
        existingZones.forEach(function (z) {
            var type = z.type || "photo";
            var shape = z.shape || "rect";
            var x = z.x || 0;
            var y = z.y || 0;
            var w = Math.max(z.width || 80, 20);
            var h = Math.max(z.height || 80, 20);
            var meta = z.meta || {};
            transform.angle = z.angle != null ? z.angle : 0;
            transform.scaleX = z.scaleX != null ? z.scaleX : 1;
            transform.scaleY = z.scaleY != null ? z.scaleY : 1;
            if (shape === "circle") {
                var s = Math.min(w, h);
                addCircleZone(type, x, y, s, s, meta, transform);
            } else if (shape === "oval") {
                addOvalZone(type, x, y, w, h, meta, transform);
            } else {
                addRectangleZone(type, x, y, w, h, meta, transform);
            }
        });
        canvas.renderAll();
    }

    function deleteSelectedZone() {
        var active = canvas.getActiveObject();
        if (!active) return;
        if (!isZoneObject(active)) return;
        canvas.remove(active);
        canvas.discardActiveObject();
        canvas.renderAll();
        updateDeleteButton();
    }

    function updateDeleteButton() {
        var active = canvas.getActiveObject();
        var btn = document.getElementById("btnDeleteSelected");
        btn.disabled = !isZoneObject(active);
    }

    function onCanvasMouseDown(opt) {
        if (!drawMode || opt.target) return;
        var p = canvas.getPointer(opt.e);
        drawStart = { x: p.x, y: p.y };
        if (drawPreview) {
            canvas.remove(drawPreview);
            drawPreview = null;
        }
        if (drawMode === "rect") {
            drawPreview = new fabric.Rect({
                left: p.x,
                top: p.y,
                width: 0,
                height: 0,
                fill: "rgba(13, 110, 253, 0.3)",
                stroke: ZONE_STROKE,
                strokeWidth: 2,
                selectable: false,
                evented: false,
            });
        } else if (drawMode === "circle") {
            drawPreview = new fabric.Circle({
                left: p.x,
                top: p.y,
                radius: 0,
                fill: "rgba(13, 110, 253, 0.3)",
                stroke: ZONE_STROKE,
                strokeWidth: 2,
                originX: "center",
                originY: "center",
                selectable: false,
                evented: false,
            });
        }
        if (drawPreview) canvas.add(drawPreview);
        canvas.renderAll();
    }

    function onCanvasMouseMove(opt) {
        if (!drawMode || !drawStart || !drawPreview) return;
        var p = canvas.getPointer(opt.e);
        var sx = drawStart.x;
        var sy = drawStart.y;
        if (drawMode === "rect") {
            var x = Math.min(sx, p.x);
            var y = Math.min(sy, p.y);
            var w = Math.max(20, Math.abs(p.x - sx));
            var h = Math.max(20, Math.abs(p.y - sy));
            drawPreview.set({ left: x, top: y, width: w, height: h });
        } else if (drawMode === "circle") {
            var r = Math.max(20, Math.sqrt((p.x - sx) * (p.x - sx) + (p.y - sy) * (p.y - sy)));
            drawPreview.set({ left: (sx + p.x) / 2, top: (sy + p.y) / 2, radius: r });
        }
        canvas.renderAll();
    }

    function onCanvasMouseUp(opt) {
        if (!drawMode || !drawStart || !drawPreview) return;
        var p = canvas.getPointer(opt.e);
        var sx = drawStart.x;
        var sy = drawStart.y;
        canvas.remove(drawPreview);
        if (drawMode === "rect") {
            var x = Math.min(sx, p.x);
            var y = Math.min(sy, p.y);
            var w = Math.max(20, Math.abs(p.x - sx));
            var h = Math.max(20, Math.abs(p.y - sy));
            addRectangleZone("photo", x, y, w, h, {});
        } else if (drawMode === "circle") {
            var r = Math.max(20, Math.sqrt((p.x - sx) * (p.x - sx) + (p.y - sy) * (p.y - sy)));
            var x = (sx + p.x) / 2 - r;
            var y = (sy + p.y) / 2 - r;
            addCircleZone("photo", x, y, r * 2, r * 2, {});
        }
        drawPreview = null;
        drawStart = null;
        canvas.renderAll();
    }

    function onKeyDown(e) {
        if (e.key === "Delete" || e.key === "Backspace") {
            e.preventDefault();
            deleteSelectedZone();
        }
        if (e.key === "Escape") {
            disableDrawMode();
        }
    }

    initCanvas();
    loadBackground(function () {
        loadExistingZones();
    });

    canvas.on("mouse:down", onCanvasMouseDown);
    canvas.on("mouse:move", onCanvasMouseMove);
    canvas.on("mouse:up", onCanvasMouseUp);

    document.addEventListener("keydown", onKeyDown);

    document.getElementById("btnDrawPhotoRect").addEventListener("click", function () {
        if (drawMode === "rect") { disableDrawMode(); return; }
        enableDrawMode("rect");
    });
    document.getElementById("btnDrawPhotoCircle").addEventListener("click", function () {
        if (drawMode === "circle") { disableDrawMode(); return; }
        enableDrawMode("circle");
    });
    document.getElementById("btnAddTextName").addEventListener("click", function () {
        disableDrawMode();
        addRectangleZone("text", 50, 50, 200, 40, { fieldName: "name", fontSize: 24 });
    });
    document.getElementById("btnDeleteSelected").addEventListener("click", deleteSelectedZone);
    document.getElementById("saveZonesBtn").addEventListener("click", saveZonesToLaravel);

    updateDeleteButton();
})();
</script>
@endpush
@endsection
