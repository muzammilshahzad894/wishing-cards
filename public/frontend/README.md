# Frontend assets

CSS and JS for the public-facing Wishing Cards site (card listing, customize page).

## Structure

- **css/app.css** – Base layout: variables, header, buttons, nav. Loaded by `layouts/app.blade.php`.
- **css/cards.css** – Home page: hero, sidebar, card grid, empty state, birthday card template styles.
- **css/cards-create.css** – Customize page: preview box, controls panel, toggle, form styles.
- **js/cards-create.js** – Customize page logic: photo upload, name field, show/hide name, download as image. Expects `window.__CARDS_CREATE__ = { namePlaceholder: "..." }` from the view.

## Usage in views

- Layout loads `frontend/css/app.css`.
- Home: `@push('styles')` + `<link href="{{ asset('frontend/css/cards.css') }}">`
- Create: `@push('styles')` for cards.css + cards-create.css; `@push('scripts')` for config, html2canvas, cards-create.js.
