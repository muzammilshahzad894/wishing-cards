{{-- Birthday Classic – premium greeting card (category: birthday, template: classic) --}}
@php
    $cardImage = $cardImage ?? null;
    $cardName = $cardName ?? 'Your Name';
    $greetingText = $greetingText ?? 'Happy Birthday';
    $cardId = $cardId ?? 'birthdayCardOutput';
    $showName = $showName ?? true;
@endphp
<div class="card-template card-birthday-classic shadow-lg" id="{{ $cardId }}">
    <div class="bclassic-inner">
        <div class="bclassic-bg"></div>
        <div class="bclassic-shine" aria-hidden="true"></div>
        <div class="bclassic-glow bclassic-glow-tl" aria-hidden="true"></div>
        <div class="bclassic-glow bclassic-glow-br" aria-hidden="true"></div>
        <div class="bclassic-corners" aria-hidden="true">
            <span class="bclassic-corner bclassic-corner-tl"></span>
            <span class="bclassic-corner bclassic-corner-tr"></span>
            <span class="bclassic-corner bclassic-corner-bl"></span>
            <span class="bclassic-corner bclassic-corner-br"></span>
        </div>
        <div class="bclassic-confetti" aria-hidden="true">
            @for ($i = 1; $i <= 10; $i++)
            <span class="bclassic-confetti-dot bclassic-dot-{{ $i }}"></span>
            @endfor
        </div>
        <div class="bclassic-balloons" aria-hidden="true">
            <span class="bclassic-balloon bclassic-balloon-1"></span>
            <span class="bclassic-balloon bclassic-balloon-2"></span>
            <span class="bclassic-balloon bclassic-balloon-3"></span>
            <span class="bclassic-balloon bclassic-balloon-4"></span>
        </div>
        <div class="bclassic-sparkles" aria-hidden="true">
            <i class="fas fa-sparkles bclassic-sparkle bclassic-sparkle-1"></i>
            <i class="fas fa-sparkles bclassic-sparkle bclassic-sparkle-2"></i>
            <i class="fas fa-sparkles bclassic-sparkle bclassic-sparkle-3"></i>
            <i class="fas fa-sparkles bclassic-sparkle bclassic-sparkle-4"></i>
            <i class="fas fa-sparkles bclassic-sparkle bclassic-sparkle-5"></i>
        </div>
        <div class="bclassic-content">
            <p class="bclassic-greeting">{{ $greetingText }}</p>
            <div class="bclassic-photo-frame">
                <div class="bclassic-photo-placeholder {{ $cardImage ? 'd-none' : '' }}" id="bdayPhotoPlaceholder">
                    <i class="fas fa-camera"></i>
                    <span>Add your photo</span>
                </div>
                <img src="{{ $cardImage ?: '' }}" alt="" class="bclassic-photo-img {{ $cardImage ? '' : 'd-none' }}" id="bdayPhotoImg" crossorigin="anonymous">
            </div>
            <p class="bclassic-name {{ ($showName ?? true) ? '' : 'd-none' }}" id="bdayName">{{ $cardName }}</p>
        </div>
    </div>
</div>
