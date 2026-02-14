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
<link rel="stylesheet" href="{{ asset('admin/css/builder.css') }}">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="{{ asset('admin/js/builder.js') }}"></script>
@endsection
