{{-- Birthday Modern – placeholder (add design when ready) --}}
@php
    $cardImage = $cardImage ?? null;
    $cardName = $cardName ?? 'Your Name';
    $greetingText = $greetingText ?? 'Happy Birthday';
    $cardId = $cardId ?? 'birthdayCardOutput';
    $showName = $showName ?? true;
@endphp
<div class="card-template card-birthday-modern" id="{{ $cardId }}">
    <div class="bmodern-inner">
        <p class="bmodern-greeting">{{ $greetingText }}</p>
        <div class="bmodern-photo-frame" id="bdayPhotoPlaceholder">
            @if($cardImage)
            <img src="{{ $cardImage }}" alt="" class="bmodern-photo-img" id="bdayPhotoImg" crossorigin="anonymous">
            @else
            <span id="bdayPhotoPlaceholder"><i class="fas fa-camera"></i> Add photo</span>
            @endif
        </div>
        <p class="bmodern-name {{ $showName ? '' : 'd-none' }}" id="bdayName">{{ $cardName }}</p>
    </div>
</div>
