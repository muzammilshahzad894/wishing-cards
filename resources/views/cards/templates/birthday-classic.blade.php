{{-- Birthday card: HTML/CSS/Bootstrap. Only image and name are customizable. --}}
@php
    $cardImage = $cardImage ?? null;
    $cardName = $cardName ?? 'Your Name';
    $greetingText = $greetingText ?? 'Happy Birthday';
    $cardId = $cardId ?? 'birthdayCardOutput';
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
            <p class="bday-name mb-0" id="bdayName">{{ $cardName }}</p>
        </div>
    </div>
</div>
<style>
.bday-card { width: 400px; height: 520px; margin: 0 auto; position: relative; border-radius: 16px; overflow: hidden; }
.bday-card-inner { position: relative; width: 100%; height: 100%; }
.bday-bg { position: absolute; inset: 0; background: linear-gradient(145deg, #fff5f0 0%, #ffe4dc 30%, #ffd4c4 60%, #ffb8a8 100%); }
.bday-decoration { position: absolute; inset: 0; pointer-events: none; opacity: 0.15; }
.bday-dots { background-image: radial-gradient(circle, #c44536 1.5px, transparent 1.5px); background-size: 24px 24px; }
.bday-content { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; text-align: center; }
.bday-greeting { font-size: 1.85rem; font-weight: 800; color: #2d1b0e; letter-spacing: 0.03em; text-shadow: 0 2px 4px rgba(255,255,255,0.6); font-family: Georgia, serif; }
.bday-photo-frame { width: 200px; height: 200px; border-radius: 50%; overflow: hidden; border: 6px solid rgba(255,255,255,0.95); box-shadow: 0 8px 24px rgba(0,0,0,0.18), inset 0 0 0 2px rgba(255,255,255,0.5); background: linear-gradient(135deg, #fff8f5 0%, #ffe8e0 100%); flex-shrink: 0; }
.bday-photo-placeholder { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #c44536; }
.bday-photo-img { width: 100%; height: 100%; object-fit: cover; }
.bday-name { font-size: 1.4rem; font-weight: 700; color: #2d1b0e; letter-spacing: 0.02em; text-shadow: 0 1px 2px rgba(255,255,255,0.5); font-family: Georgia, serif; }
.d-none { display: none !important; }
</style>
