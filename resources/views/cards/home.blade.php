@extends('layouts.app')

@section('title', $currentCategory ? ($categories[$currentCategory] ?? 'Greeting Cards') : 'Greeting Cards')

@section('header-categories')
<div class="dropdown">
    <button class="nav-categories-btn dropdown-toggle" type="button" id="mobileCategoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-th-large"></i> {{ $currentCategory ? ($categories[$currentCategory] ?? 'Categories') : 'All' }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end cards-dropdown-menu" aria-labelledby="mobileCategoryDropdown">
        <li><a class="dropdown-item {{ $currentCategory === null ? 'active' : '' }}" href="{{ route('cards.home') }}"><i class="fas fa-layer-group me-2"></i>All</a></li>
        @foreach($categories as $slug => $label)
        <li><a class="dropdown-item {{ $slug === $currentCategory ? 'active' : '' }}" href="{{ route('cards.category', $slug) }}">{{ $label }}</a></li>
        @endforeach
    </ul>
</div>
@endsection

@section('content')
<div class="cards-hero">
    <div class="cards-hero-inner">
        <h1 class="cards-hero-title">Create Your Card</h1>
        <p class="cards-hero-subtitle">Choose a design and personalize it in seconds. Send warmth to someone special.</p>
    </div>
</div>
<div class="cards-page">
    <aside class="cards-sidebar">
        <div class="sidebar-header">
            <span class="sidebar-label">Categories</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('cards.home') }}" class="sidebar-link {{ $currentCategory === null ? 'active' : '' }}">
                <i class="fas fa-layer-group sidebar-icon"></i>
                <span>All designs</span>
            </a>
            @foreach($categories as $slug => $label)
            <a href="{{ route('cards.category', $slug) }}" class="sidebar-link {{ $slug === $currentCategory ? 'active' : '' }}">
                <i class="fas fa-gift sidebar-icon"></i>
                <span>{{ $label }}</span>
            </a>
            @endforeach
        </nav>
    </aside>
    <div class="cards-main">
        <div class="cards-main-header">
            <h2 class="cards-section-title">{{ $currentCategory ? ($categories[$currentCategory] ?? 'Designs') : 'All designs' }}</h2>
            @if(!$designs->isEmpty())
            <p class="cards-section-count">{{ $designs->count() }} {{ $designs->count() === 1 ? 'design' : 'designs' }} available</p>
            @endif
        </div>
        @if($designs->isEmpty())
            <div class="cards-empty">
                <div class="cards-empty-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3 class="cards-empty-title">No designs yet</h3>
                <p class="cards-empty-text">{{ $currentCategory ? 'This category doesn\'t have any designs yet. Check back soon!' : 'Designs will appear here once they\'re added.' }}</p>
            </div>
        @else
            <div class="cards-grid">
                @foreach($designs as $design)
                <a href="{{ route('cards.create', $design) }}" class="card-tile">
                    <div class="card-tile-preview">
                        @include($design->getTemplateViewPath(), [
                            'cardImage' => null,
                            'cardName' => $design->getTemplateDefaults()['namePlaceholder'] ?? 'Your Name',
                            'greetingText' => $design->getTemplateDefaults()['greetingText'] ?? 'Happy Birthday',
                            'cardId' => 'preview-' . $design->id,
                            'showName' => true,
                        ])
                    </div>
                    <div class="card-tile-footer">
                        <span class="card-tile-title">{{ $design->name }}</span>
                        <span class="card-tile-cta">Customize <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/cards.css') }}">
@endpush
