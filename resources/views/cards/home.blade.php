@extends('layouts.app')

@section('title', $currentCategory ? ($categories[$currentCategory] ?? 'Greeting Cards') : 'Greeting Cards')

@section('header-categories')
<div class="dropdown">
    <button class="nav-categories-btn dropdown-toggle" type="button" id="mobileCategoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-th-large"></i> <span class="d-inline d-sm-none">Menu</span><span class="d-none d-sm-inline">{{ $currentCategory ? ($categories[$currentCategory] ?? 'Categories') : 'All' }}</span>
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
        <p class="cards-hero-subtitle">Choose from beautiful templates and personalize in seconds.</p>
        <a href="#cards-main" class="btn btn-hero-primary">
            <i class="fas fa-palette me-2" aria-hidden="true"></i> Browse Designs
        </a>
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
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/cards.css') }}">
@endpush

@push('scripts')
<script>
(function() {
    var designsData = @json($designs->isEmpty() ? [] : $designs->map(fn($d) => ['name' => $d->name, 'url' => route('cards.create', $d)])->values());
    var input = document.getElementById('navbar-search-input');
    var resultsEl = document.getElementById('navbar-search-results');
    if (!input || !resultsEl || !designsData.length) return;

    var hideTimeout;
    function showResults(items) {
        resultsEl.innerHTML = '';
        resultsEl.hidden = false;
        input.setAttribute('aria-expanded', 'true');
        if (items.length === 0) {
            var empty = document.createElement('div');
            empty.className = 'navbar-search-item navbar-search-empty';
            empty.textContent = 'No matching designs';
            resultsEl.appendChild(empty);
            return;
        }
        items.slice(0, 8).forEach(function(d) {
            var a = document.createElement('a');
            a.href = d.url;
            a.className = 'navbar-search-item dropdown-item';
            a.setAttribute('role', 'option');
            a.textContent = d.name;
            a.addEventListener('click', function(e) { e.preventDefault(); window.location.href = d.url; });
            resultsEl.appendChild(a);
        });
    }
    function hideResults() {
        hideTimeout = setTimeout(function() {
            resultsEl.hidden = true;
            resultsEl.innerHTML = '';
            input.setAttribute('aria-expanded', 'false');
        }, 150);
    }
    function filterDesigns(query) {
        var q = (query || '').trim().toLowerCase();
        if (!q) return [];
        return designsData.filter(function(d) { return d.name.toLowerCase().indexOf(q) !== -1; });
    }
    input.addEventListener('input', function() {
        clearTimeout(hideTimeout);
        var q = this.value.trim();
        showResults(q ? filterDesigns(q) : []);
    });
    input.addEventListener('focus', function() {
        clearTimeout(hideTimeout);
        if (this.value.trim()) showResults(filterDesigns(this.value));
    });
    input.addEventListener('blur', hideResults);
    resultsEl.addEventListener('mousedown', function(e) {
        e.preventDefault();
    });
})();
</script>
@endpush
