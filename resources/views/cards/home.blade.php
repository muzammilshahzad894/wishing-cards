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

@section('styles')
<style>
/* Hero */
.cards-hero {
    background: linear-gradient(135deg, rgba(199, 54, 89, 0.06) 0%, rgba(244, 162, 97, 0.04) 100%);
    border-bottom: 1px solid var(--border);
    padding: 2rem 1.5rem;
}
.cards-hero-inner { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
.cards-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 600;
    color: var(--text);
    letter-spacing: -0.02em;
    margin: 0 0 0.35em 0;
}
.cards-hero-subtitle {
    font-size: 0.9375rem;
    color: var(--text-muted);
    margin: 0;
    max-width: 420px;
    line-height: 1.5;
}

/* Layout */
.cards-page {
    display: flex;
    flex-direction: row;
    min-height: calc(100vh - 56px - 120px);
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
}
.cards-sidebar {
    width: 260px;
    flex-shrink: 0;
    background: var(--card-bg);
    border-right: 1px solid var(--border);
    padding: 1.5rem 0;
    position: sticky;
    top: 57px;
    align-self: flex-start;
    max-height: calc(100vh - 57px);
    overflow-y: auto;
}
.sidebar-header { padding: 0 1.25rem 1rem; }
.sidebar-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--text-muted);
}
.sidebar-nav { display: flex; flex-direction: column; gap: 0.2rem; padding: 0 0.75rem; }
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.65rem 1rem;
    font-size: 0.9rem;
    color: var(--text);
    text-decoration: none;
    border-radius: 10px;
    transition: background 0.2s ease, color 0.2s ease;
}
.sidebar-link:hover { background: var(--bg-subtle); color: var(--primary); }
.sidebar-link.active {
    background: linear-gradient(135deg, rgba(199, 54, 89, 0.12) 0%, rgba(199, 54, 89, 0.06) 100%);
    color: var(--primary);
    font-weight: 600;
}
.sidebar-icon { font-size: 0.9em; opacity: 0.85; width: 1.25em; text-align: center; }

/* Main content */
.cards-main {
    flex: 1;
    min-width: 0;
    padding: 2rem 2rem 2.5rem;
    overflow: auto;
}
.cards-main-header { margin-bottom: 1.5rem; }
.cards-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.01em;
}
.cards-section-count {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
}

/* Empty state */
.cards-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px dashed var(--border);
    max-width: 400px;
    margin: 2rem auto 0;
}
.cards-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 1.25rem;
    background: var(--bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cards-empty-icon i { font-size: 1.75rem; color: var(--text-muted); }
.cards-empty-title { font-size: 1.125rem; font-weight: 600; color: var(--text); margin: 0 0 0.5rem 0; }
.cards-empty-text { font-size: 0.9375rem; color: var(--text-muted); margin: 0; line-height: 1.5; }

/* Card grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}
.card-tile {
    text-decoration: none;
    color: inherit;
    display: block;
    background: var(--card-bg);
    border-radius: var(--radius);
    overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.2s ease;
}
.card-tile:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.1);
    border-color: rgba(199, 54, 89, 0.2);
}
.card-tile:hover .card-tile-title { color: var(--primary); }
.card-tile:hover .card-tile-cta { opacity: 1; }
.card-tile-preview {
    aspect-ratio: 400/520;
    background: linear-gradient(180deg, var(--bg-subtle) 0%, #f5f0ea 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.card-tile-preview .bday-card { transform: scale(0.55); transform-origin: center center; transition: transform 0.25s ease; }
.card-tile:hover .card-tile-preview .bday-card { transform: scale(0.58); }
.card-tile-footer {
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    border-top: 1px solid var(--border);
}
.card-tile-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text);
    margin: 0;
    transition: color 0.2s ease;
    flex: 1;
    min-width: 0;
}
.card-tile-cta {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--primary);
    opacity: 0.85;
    transition: opacity 0.2s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

/* Mobile dropdown */
.cards-dropdown-menu .dropdown-item.active { background: rgba(199, 54, 89, 0.12); color: var(--primary); }

@media (max-width: 991px) {
    .cards-hero { padding: 1.5rem 1rem; }
    .cards-page { min-height: auto; }
    .cards-sidebar { display: none; }
    .cards-main { padding: 1.25rem 1rem 2rem; }
    .cards-main-header { margin-bottom: 1.25rem; }
    .cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    .card-tile-preview .bday-card { transform: scale(0.4); }
    .card-tile:hover .card-tile-preview .bday-card { transform: scale(0.42); }
    .card-tile-footer { padding: 0.75rem 1rem; flex-wrap: wrap; }
    .card-tile-cta { font-size: 0.75rem; }
}
@media (min-width: 1200px) {
    .cards-grid { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.75rem; }
    .card-tile-preview .bday-card { transform: scale(0.6); }
    .card-tile:hover .card-tile-preview .bday-card { transform: scale(0.63); }
}
</style>
@endsection
