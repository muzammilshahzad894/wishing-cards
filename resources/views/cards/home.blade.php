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
<section class="cards-hero" aria-label="Hero">
    <div class="cards-hero-bg"></div>
    <div class="container cards-hero-inner">
        <h1 class="cards-hero-title">Create Your Perfect Card</h1>
        <p class="cards-hero-subtitle">Choose from beautiful templates and personalize in seconds. Send warmth to someone special.</p>
        <div class="cards-hero-actions">
            <a href="#cards-main" class="btn btn-hero-primary">
                <i class="fas fa-palette me-2" aria-hidden="true"></i> Browse Designs
            </a>
            <a href="#how-it-works" class="btn btn-hero-secondary">
                How it Works <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>

<div class="cards-page">
    <aside class="cards-sidebar" aria-label="Filter by category">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="sidebar-label">Categories</span>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('cards.home') }}" class="sidebar-link {{ $currentCategory === null ? 'active' : '' }}">
                    <i class="fas fa-layer-group sidebar-icon" aria-hidden="true"></i>
                    <span>All designs</span>
                </a>
                @foreach($categories as $slug => $label)
                <a href="{{ route('cards.category', $slug) }}" class="sidebar-link {{ $slug === $currentCategory ? 'active' : '' }}">
                    <i class="fas fa-gift sidebar-icon" aria-hidden="true"></i>
                    <span>{{ $label }}</span>
                </a>
                @endforeach
            </nav>
        </div>
    </aside>
    <div class="cards-main" id="cards-main">
        <div class="cards-main-header">
            <h2 class="cards-section-title">{{ $currentCategory ? ($categories[$currentCategory] ?? 'Designs') : 'All designs' }}</h2>
            @if(!$designs->isEmpty())
            <p class="cards-section-count">{{ $designs->count() }} {{ $designs->count() === 1 ? 'design' : 'designs' }} available</p>
            @endif
        </div>
        @if($designs->isEmpty())
            <div class="cards-empty">
                <div class="cards-empty-icon">
                    <i class="fas fa-palette" aria-hidden="true"></i>
                </div>
                <h3 class="cards-empty-title">No designs yet</h3>
                <p class="cards-empty-text">{{ $currentCategory ? 'This category doesn\'t have any designs yet. Check back soon!' : 'Designs will appear here once they\'re added.' }}</p>
            </div>
        @else
            <div class="cards-grid">
                @foreach($designs as $design)
                <a href="{{ route('cards.create', $design) }}" class="card-tile">
                    <div class="card-tile-preview">
                        <span class="card-tile-overlay" aria-hidden="true">Customize</span>
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
                        <span class="card-tile-cta">Customize <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<section class="cards-how-it-works" id="how-it-works" aria-label="How it works">
    <div class="container">
        <h2 class="how-it-works-title">How it Works</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-sm-6 col-lg-4">
                <div class="how-step">
                    <span class="how-step-num" aria-hidden="true">1</span>
                    <h3 class="how-step-title">Choose a design</h3>
                    <p class="how-step-text">Browse templates and pick one you love.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="how-step">
                    <span class="how-step-num" aria-hidden="true">2</span>
                    <h3 class="how-step-title">Personalize</h3>
                    <p class="how-step-text">Add your photo and message in seconds.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="how-step">
                    <span class="how-step-num" aria-hidden="true">3</span>
                    <h3 class="how-step-title">Share or download</h3>
                    <p class="how-step-text">Send the link or download your card.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/cards.css') }}">
@endpush
