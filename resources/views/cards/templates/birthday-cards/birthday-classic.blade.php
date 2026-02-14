{{-- Birthday card: HTML/CSS/Bootstrap. Only image and name are customizable. --}}
@php
    $cardImage = $cardImage ?? null;
    $cardName = $cardName ?? 'Your Name';
    $greetingText = $greetingText ?? 'Happy Birthday';
    $cardId = $cardId ?? 'birthdayCardOutput';
    $showName = $showName ?? true;
@endphp
<div class="bday-card shadow-lg" id="{{ $cardId }}">
    <div class="bday-card-inner">
        <div class="bday-bg"></div>
        <div class="bday-decoration bday-dots"></div>
        <div class="bday-content position-relative">
            <p class="bday-greeting mb-0">{{ $greetingText }}</p>
            <div class="bday-photo-frame my-4">
                <div class="bday-photo-placeholder {{ $cardImage ? 'd-none' : '' }}" id="bdayPhotoPlaceholder">
                    <i class="fas fa-camera fa-3x"></i>
                    <span class="small mt-2 d-block">Add your photo</span>
                </div>
                <img src="{{ $cardImage ?: '' }}" alt="" class="bday-photo-img {{ $cardImage ? '' : 'd-none' }}" id="bdayPhotoImg" crossorigin="anonymous">
            </div>
            <p class="bday-name mb-0 {{ ($showName ?? true) ? '' : 'd-none' }}" id="bdayName">{{ $cardName }}</p>
        </div>
    </div>
</div>
{{-- Styles for .bday-card are in frontend/css/cards.css --}}
