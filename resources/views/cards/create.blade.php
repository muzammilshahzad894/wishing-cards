@extends('layouts.app')

@section('title', $design->name)

@section('header-right')
<a href="{{ route('cards.home') }}" class="btn btn-back"><i class="fas fa-arrow-left me-1"></i> All designs</a>
@stop

@section('content')
@php
    $defaults = $design->getTemplateDefaults();
@endphp
<div class="container create-page">
    <header class="create-header">
        <h1 class="create-title">{{ $design->name }}</h1>
        <p class="create-subtitle">Add your photo and optional name, then download your card.</p>
    </header>

    <div class="create-layout">
        <div class="create-preview-col">
            <div class="preview-box">
                <span class="preview-badge">Live preview</span>
                <div class="preview-inner" id="cardCanvasWrap">
                    @include($design->getTemplateViewPath(), [
                        'cardImage' => null,
                        'cardName' => $defaults['namePlaceholder'],
                        'greetingText' => $defaults['greetingText'],
                        'showName' => true,
                    ])
                </div>
            </div>
        </div>
        <div class="create-controls-col">
            <div class="controls-panel">
                <h2 class="controls-heading">Customize</h2>
                <div class="control-group">
                    <label class="control-label">Photo</label>
                    <button type="button" class="btn btn-upload w-100" id="choosePhotoBtn">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose photo
                    </button>
                    <input type="file" id="photoInput" accept="image/*" class="d-none">
                </div>
                <div class="control-group control-group-name" id="nameOptionGroup">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="control-label mb-0">Name on card</label>
                        <label class="toggle-switch" title="Show or hide name on the card">
                            <input type="checkbox" id="showNameToggle" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <input type="text" class="form-control form-control-custom" id="nameInput" placeholder="{{ e($defaults['namePlaceholder']) }}" value="" aria-label="Name on card">
                </div>
                <div class="control-group">
                    <button type="button" class="btn btn-download w-100" id="savePhotoBtn">
                        <i class="fas fa-download me-2"></i>Save as image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.create-page { padding: 2rem 0 3rem; max-width: 1200px; margin: 0 auto; }
.create-header { margin-bottom: 2rem; }
.create-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
.create-subtitle { font-size: 1rem; color: var(--text-muted); margin: 0; }
.create-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; align-items: start; }
@media (min-width: 992px) {
    .create-controls-col { position: sticky; top: 5.5rem; }
}
.preview-box { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; }
.preview-badge { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 1rem; }
.preview-inner { display: flex; justify-content: center; align-items: center; min-height: 420px; }
.controls-panel { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.75rem; }
.controls-heading { font-size: 1.125rem; font-weight: 700; color: var(--text); margin-bottom: 1.5rem; }
.control-group { margin-bottom: 1.5rem; }
.control-group:last-child { margin-bottom: 0; }
.control-label { font-size: 0.875rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 0.5rem; }
.btn-upload { background: var(--bg-subtle); border: 2px dashed var(--border); color: var(--text); padding: 0.875rem 1.25rem; border-radius: var(--radius-sm); font-weight: 600; transition: border-color 0.2s, background 0.2s; }
.btn-upload:hover { border-color: var(--primary-light); background: rgba(199, 54, 89, 0.06); color: var(--text); }
.form-control-custom { border-radius: var(--radius-sm); padding: 0.75rem 1rem; border: 1px solid var(--border); font-size: 1rem; }
.form-control-custom:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(199, 54, 89, 0.12); }
.btn-download { background: var(--primary); border: none; color: #fff; padding: 1rem 1.25rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 1rem; transition: background 0.2s, transform 0.2s; }
.btn-download:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); }
.toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; cursor: pointer; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; inset: 0; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 999px; transition: 0.25s; }
.toggle-slider::before { content: ''; position: absolute; height: 18px; width: 18px; left: 2px; bottom: 2px; background: var(--card-bg); border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: 0.25s; }
.toggle-switch input:checked + .toggle-slider { background: var(--primary); border-color: var(--primary); }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }
.toggle-switch input:focus + .toggle-slider { box-shadow: 0 0 0 3px rgba(199, 54, 89, 0.2); }
@media (max-width: 991px) {
    .create-layout { grid-template-columns: 1fr; }
    .create-controls-col { position: static; }
    .preview-inner { min-height: 360px; }
}
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function() {
    const namePlaceholder = {!! json_encode($defaults['namePlaceholder']) !!};
    const photoPlaceholder = document.getElementById('bdayPhotoPlaceholder');
    const photoImg = document.getElementById('bdayPhotoImg');
    const cardNameEl = document.getElementById('bdayName');
    const nameInput = document.getElementById('nameInput');
    const showNameToggle = document.getElementById('showNameToggle');
    const nameOptionGroup = document.getElementById('nameOptionGroup');
    const choosePhotoBtn = document.getElementById('choosePhotoBtn');
    const photoInput = document.getElementById('photoInput');
    const savePhotoBtn = document.getElementById('savePhotoBtn');
    const cardOutput = document.getElementById('birthdayCardOutput');

    function updateNameVisibility() {
        const show = showNameToggle && showNameToggle.checked;
        if (cardNameEl) {
            if (show) cardNameEl.classList.remove('d-none');
            else cardNameEl.classList.add('d-none');
        }
        var textInput = nameOptionGroup && nameOptionGroup.querySelector('input[type="text"]');
        if (textInput) textInput.disabled = !show;
    }

    if (showNameToggle) {
        showNameToggle.addEventListener('change', updateNameVisibility);
    }

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
