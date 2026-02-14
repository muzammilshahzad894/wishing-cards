# Admin assets

CSS and JS for the Wishing Cards admin panel.

## Structure

### CSS

- **css/app.css** – Base: variables, sidebar, header, cards, tables, forms, modals, mobile. Loaded by `admin/layout.blade.php`.
- **css/auth.css** – Login page only. Loaded by `admin/auth/login.blade.php`.
- **css/builder.css** – Card builder: template grid, upload zone, card preview styles. Loaded by builder view.
- **css/profile.css** – Profile page: avatar, account stats. Loaded by profile edit view.

### JS

- **js/app.js** – Layout: tooltips, mobile menu, alert auto-hide, form submit loading. Loaded by `admin/layout.blade.php`.
- **js/auth.js** – Login: password visibility toggle, alert auto-hide, submit button state. Loaded by login view.
- **js/designs.js** – Designs list: preview modal (iframe src on show, clear on hidden). Loaded by designs index view.
- **js/builder.js** – Card builder: template selection, dimensions, image upload/drag-drop, html2canvas download. Loaded by builder view (after html2canvas CDN).

## Usage in views

- Layout loads `admin/css/app.css` and `admin/js/app.js`.
- Login: `admin/css/auth.css`, `admin/js/auth.js` (no jQuery on login).
- Designs index: `@section('scripts')` with `admin/js/designs.js`.
- Builder: `@section('styles')` builder.css, `@section('scripts')` html2canvas + `admin/js/builder.js`.
- Profile edit: `@section('styles')` profile.css.
