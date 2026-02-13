@extends('layouts.app')

@section('title', $currentCategory ? ($categories[$currentCategory] ?? 'Greeting Cards') : 'Greeting Cards')

@section('header-categories')
<div class="dropdown">
    <button class="nav-categories-btn dropdown-toggle" type="button" id="mobileCategoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-th-large"></i> {{ $currentCategory ? ($categories[$currentCategory] ?? 'Categories') : 'All' }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileCategoryDropdown">
        <li><a class="dropdown-item {{ $currentCategory === null ? 'active' : '' }}" href="{{ route('cards.home') }}">All</a></li>
        @foreach($categories as $slug => $label)
        <li><a class="dropdown-item {{ $slug === $currentCategory ? 'active' : '' }}" href="{{ route('cards.category', $slug) }}">{{ $label }}</a></li>
        @endforeach
    </ul>
</div>
@endsection

@section('content')
<div class="cards-page">
    <aside class="cards-sidebar">
        <nav class="sidebar-nav">
            <p class="sidebar-label">Categories</p>
            <a href="{{ route('cards.home') }}" class="sidebar-link {{ $currentCategory === null ? 'active' : '' }}">All</a>
            @foreach($categories as $slug => $label)
            <a href="{{ route('cards.category', $slug) }}" class="sidebar-link {{ $slug === $currentCategory ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </aside>
    <div class="cards-main">
        @if($designs->isEmpty())
            <div class="cards-empty">
                <i class="fas fa-palette"></i>
                <p>{{ $currentCategory ? 'No designs in this category yet.' : 'No designs yet.' }}</p>
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
                    <p class="card-tile-title">{{ $design->name }}</p>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.cards-page { display: flex; flex-direction: row; min-height: calc(100vh - 56px); width: 100%; }
.cards-sidebar { width: 240px; flex-shrink: 0; background: var(--card-bg); border-right: 1px solid var(--border); padding: 1.25rem 0; }
.sidebar-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); padding: 0 1rem; margin-bottom: 0.75rem; }
.sidebar-nav { display: flex; flex-direction: column; gap: 0.15rem; }
.sidebar-link { display: block; padding: 0.5rem 1rem; font-size: 0.9375rem; color: var(--text); text-decoration: none; border-radius: 0 8px 8px 0; transition: background 0.15s, color 0.15s; }
.sidebar-link:hover { background: var(--bg-subtle); color: var(--primary); }
.sidebar-link.active { background: rgba(199, 54, 89, 0.1); color: var(--primary); font-weight: 600; }
.cards-main { flex: 1; min-width: 0; padding: 1.5rem; overflow: auto; }
.cards-empty { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
.cards-empty i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
.cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; }
.card-tile { text-decoration: none; color: inherit; display: block; background: var(--card-bg); border-radius: var(--radius-sm); overflow: hidden; box-shadow: var(--shadow); transition: transform 0.2s, box-shadow 0.2s; }
.card-tile:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
.card-tile:hover .card-tile-title { color: var(--primary); }
.card-tile-preview { aspect-ratio: 400/520; background: var(--bg-subtle); display: flex; align-items: center; justify-content: center; overflow: hidden; }
.card-tile-preview .bday-card { transform: scale(0.55); transform-origin: center center; }
.card-tile-title { font-size: 0.9375rem; font-weight: 600; padding: 0.75rem 1rem; margin: 0; transition: color 0.2s; }
@media (max-width: 991px) {
    .cards-sidebar { display: none; }
    .cards-main { padding: 1rem; }
    .cards-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .card-tile-preview .bday-card { transform: scale(0.4); }
}
@media (min-width: 1200px) {
    .cards-grid { grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); }
    .card-tile-preview .bday-card { transform: scale(0.65); }
}
</style>
@endsection
