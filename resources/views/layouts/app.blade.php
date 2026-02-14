<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Create Birthday Card') – Wishing Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/app.css') }}">
    @stack('styles')
    @yield('styles')
</head>
<body>
    <header class="site-header">
        <div class="container">
            <nav class="navbar-nav-custom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <a class="navbar-brand-custom text-decoration-none d-flex align-items-center" href="{{ url('/') }}">
                    <span class="navbar-brand-text">Wishing Cards</span>
                </a>
                <div class="navbar-right d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0 justify-content-end order-2 order-md-1">
                    @hasSection('header-categories')
                        <div class="d-lg-none">
                            @yield('header-categories')
                        </div>
                    @endif
                    <div class="navbar-search-wrap position-relative">
                        <form action="{{ url('/') }}" method="get" class="navbar-search-form" role="search" autocomplete="off">
                            <label for="navbar-search-input" class="visually-hidden">Search designs</label>
                            <i class="fas fa-search navbar-search-icon" aria-hidden="true"></i>
                            <input type="text" id="navbar-search-input" name="q" class="navbar-search-input form-control" placeholder="Search designs..." value="{{ request('q') }}" aria-label="Search designs" aria-autocomplete="list" aria-controls="navbar-search-results" aria-expanded="false">
                        </form>
                        <div id="navbar-search-results" class="navbar-search-results dropdown-menu shadow" role="listbox" aria-label="Search results" hidden></div>
                    </div>
                </div>
                @yield('header-right')
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
