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
            <nav class="navbar-nav-custom d-flex align-items-center justify-content-between gap-3 flex-nowrap">
                <a class="navbar-brand-custom text-decoration-none d-flex align-items-center" href="{{ url('/') }}">
                    <span class="navbar-brand-text">Wishing Cards</span>
                </a>
                <div class="navbar-search-wrap d-none d-md-flex flex-grow-1 mx-3 mx-xl-4">
                    <form action="{{ url('/') }}" method="get" class="navbar-search-form w-100" role="search">
                        <label for="navbar-search-input" class="visually-hidden">Search designs</label>
                        <i class="fas fa-search navbar-search-icon"></i>
                        <input type="search" id="navbar-search-input" name="q" class="navbar-search-input form-control" placeholder="Search designs..." value="{{ request('q') }}" aria-label="Search designs">
                    </form>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    @yield('header-right')
                    @hasSection('header-categories')
                        <div class="d-lg-none">
                            @yield('header-categories')
                        </div>
                    @endif
                    <a href="{{ route('cards.home') }}#cards-main" class="btn btn-create-custom">
                        <i class="fas fa-plus me-1" aria-hidden="true"></i> Create Card
                    </a>
                </div>
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
