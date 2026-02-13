@extends('layouts.app')

@section('title', 'Create Birthday Card')

@section('header-right')
@stop

@section('content')
<div class="hero-section text-center mb-5 py-4">
    <h1 class="hero-title mb-2">Create Free Birthday Photo Frames And Wishes Cards Online</h1>
    <p class="hero-subtitle text-muted">Choose a design, add your photo and name, then save your card.</p>
</div>
@if($designs->isEmpty())
    <div class="empty-state-card text-center py-5">
        <i class="fas fa-images fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">No card designs available at the moment. Check back later!</p>
    </div>
@else
    <div class="designs-grid">
        @foreach($designs as $design)
        <a href="{{ route('cards.create', $design) }}" class="design-card-link">
            <div class="design-card">
                <div class="design-card-preview">
                    @include('cards.templates.' . $design->template_key, [
                        'cardImage' => null,
                        'cardName' => $design->getTemplateDefaults()['namePlaceholder'] ?? 'Your Name',
                        'greetingText' => $design->getTemplateDefaults()['greetingText'] ?? 'Happy Birthday',
                        'cardId' => 'preview-' . $design->id,
                    ])
                </div>
                <div class="design-card-body">
                    <h6 class="design-card-title">{{ $design->name }}</h6>
                    <span class="design-card-cta">Use this design <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif
@endsection

@section('styles')
<style>
    .hero-section { padding: 1rem 0; }
    .hero-title { font-size: 1.75rem; font-weight: 700; color: var(--text); line-height: 1.3; }
    .hero-subtitle { font-size: 1rem; max-width: 540px; margin-left: auto; margin-right: auto; }
    .empty-state-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
    .designs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .design-card-link { text-decoration: none; color: inherit; display: block; }
    .design-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; height: 100%; display: flex; flex-direction: column; }
    .design-card-link:hover .design-card { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
    .design-card-preview { aspect-ratio: 400/520; background: #f5f0e8; display: flex; align-items: center; justify-content: center; padding: 0.5rem; overflow: hidden; }
    .design-card-preview .bday-card { transform: scale(0.6); transform-origin: center center; }
    .design-card-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: center; text-align: center; }
    .design-card-title { font-weight: 600; margin-bottom: 0.35rem; color: var(--text); }
    .design-card-cta { font-size: 0.875rem; color: var(--text-muted); }
</style>
@endsection
