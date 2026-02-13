@extends('layouts.app')

@section('title', $design->name)

@section('header-right')
<a href="{{ route('cards.home') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> All designs</a>
@stop

@section('content')
@php
    $defaults = $design->getTemplateDefaults();
@endphp
<div class="page-title mb-4">
    <h1 class="mb-1">{{ $design->name }}</h1>
    <p class="text-muted mb-0">Add your photo and name. The card design stays the same.</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="preview-card-wrap">
            <div class="preview-label">Preview</div>
            <div class="card-canvas-wrap" id="cardCanvasWrap">
                @include('cards.templates.' . $design->template_key, [
                    'cardImage' => null,
                    'cardName' => $defaults['namePlaceholder'],
                    'greetingText' => $defaults['greetingText'],
                ])
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="controls-card">
            <h6 class="controls-title">Customize your card</h6>
            <div class="mb-4">
                <button type="button" class="btn btn-primary btn-choose-photo w-100 py-3" id="choosePhotoBtn">
                    <i class="fas fa-image me-2"></i>Choose photo
                </button>
                <input type="file" id="photoInput" accept="image/*" class="d-none">
            </div>
            <div class="mb-4">
                <label class="form-label">Name on card</label>
                <input type="text" class="form-control form-control-lg rounded-3" id="nameInput" placeholder="{{ $defaults['namePlaceholder'] }}" value="">
            </div>
            <button type="button" class="btn btn-success btn-save w-100 py-3" id="savePhotoBtn">
                <i class="fas fa-download me-2"></i>Save as image
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
    .controls-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 1.75rem; }
    .controls-title { font-weight: 600; margin-bottom: 1.25rem; color: var(--text); }
    .btn-choose-photo { border-radius: 12px; font-weight: 600; }
    .btn-save { border-radius: 12px; font-weight: 600; }
    @media (max-width: 991px) {
        .card-canvas-wrap { min-height: 380px; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function() {
    const namePlaceholder = {{ json_encode($defaults['namePlaceholder']) }};
    const photoPlaceholder = document.getElementById('bdayPhotoPlaceholder');
    const photoImg = document.getElementById('bdayPhotoImg');
    const cardNameEl = document.getElementById('bdayName');
    const nameInput = document.getElementById('nameInput');
    const choosePhotoBtn = document.getElementById('choosePhotoBtn');
    const photoInput = document.getElementById('photoInput');
    const savePhotoBtn = document.getElementById('savePhotoBtn');
    const cardOutput = document.getElementById('birthdayCardOutput');

    if (nameInput && cardNameEl) {
        nameInput.addEventListener('input', function() {
            cardNameEl.textContent = this.value.trim() || namePlaceholder;
        });
    }

    choosePhotoBtn.addEventListener('click', function() { photoInput.click(); });

    photoInput.addEventListener('change', function() {
        const f = this.files[0];
        if (!f || !f.type.startsWith('image/')) return;
        const r = new FileReader();
        r.onload = function() {
            photoImg.src = r.result;
            photoImg.classList.remove('d-none');
            if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
        };
        r.readAsDataURL(f);
        this.value = '';
    });

    savePhotoBtn.addEventListener('click', function() {
        savePhotoBtn.disabled = true;
        savePhotoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        html2canvas(cardOutput, {
            scale: 1,
            useCORS: true,
            allowTaint: true,
            backgroundColor: null,
        }).then(function(canvas) {
            var a = document.createElement('a');
            a.download = 'birthday-card-' + Date.now() + '.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
            savePhotoBtn.disabled = false;
            savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save as image';
        }).catch(function() {
            savePhotoBtn.disabled = false;
            savePhotoBtn.innerHTML = '<i class="fas fa-download me-2"></i>Save as image';
            alert('Failed to save. Try again.');
        });
    });
})();
</script>
@endsection
