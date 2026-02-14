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
                        'cardId' => 'birthdayCardOutput',
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

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/cards.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/cards-create.css') }}">
<link rel="stylesheet" href="{{ $design->getTemplateCssUrl() }}">
@endpush

@push('scripts')
<script>window.__CARDS_CREATE__ = {!! json_encode(['namePlaceholder' => $defaults['namePlaceholder']]) !!};</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="{{ asset('frontend/js/cards-create.js') }}"></script>
@endpush
