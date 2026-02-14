{{-- Birthday Modern – dark elegant, circular photo, floral, script + serif typography --}}
@php
    $cardImage = $cardImage ?? null;
    $cardName = $cardName ?? 'Your Name';
    $greetingText = $greetingText ?? 'Fifty';
    $cardId = $cardId ?? 'card-canvas-output';
    $showName = $showName ?? true;
@endphp
<div class="card-template card-birthday-modern shadow-lg" id="{{ $cardId }}">
    <div class="bmodern-inner">
        <div class="bmodern-bg"></div>
        <div class="bmodern-shine" aria-hidden="true"></div>
        <div class="bmodern-sparkles" aria-hidden="true">
            <span class="bmodern-sparkle-dot bmodern-dot-1"></span>
            <span class="bmodern-sparkle-dot bmodern-dot-2"></span>
            <span class="bmodern-sparkle-dot bmodern-dot-3"></span>
            <span class="bmodern-sparkle-dot bmodern-dot-4"></span>
            <span class="bmodern-sparkle-dot bmodern-dot-5"></span>
            <span class="bmodern-sparkle-dot bmodern-dot-6"></span>
        </div>
        <div class="bmodern-content">
            <div class="bmodern-photo-wrap">
                <div class="bmodern-photo-frame">
                    <div class="bmodern-photo-zoom-wrap" id="card-photo-zoom-wrap">
                        <div class="bmodern-photo-placeholder {{ $cardImage ? 'd-none' : '' }}" data-card-photo-placeholder id="bdayPhotoPlaceholder">
                            <i class="fas fa-camera"></i>
                            <span>Add your photo</span>
                        </div>
                        <img src="{{ $cardImage ?: '' }}" alt="" class="bmodern-photo-img {{ $cardImage ? '' : 'd-none' }}" id="card-photo-preview" crossorigin="anonymous">
                    </div>
                    <div class="bmodern-floral" aria-hidden="true"></div>
                </div>
            </div>
            <p class="bmodern-script" id="card-script-text">{{ $greetingText }}</p>
            <p class="bmodern-greeting">HAPPY BIRTHDAY</p>
            <p class="bmodern-name {{ ($showName ?? true) ? '' : 'd-none' }}" id="card-name-display">{{ $cardName }}</p>
        </div>
    </div>
</div>
