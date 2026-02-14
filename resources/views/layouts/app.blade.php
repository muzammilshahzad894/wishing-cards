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
            <nav class="d-flex align-items-center justify-content-between gap-3">
                <a class="navbar-brand text-decoration-none" href="{{ url('/') }}">Wishing Cards</a>
                <div class="d-flex align-items-center gap-2">
                    @yield('header-right')
                    @hasSection('header-categories')
                        <div class="d-lg-none">
                            @yield('header-categories')
                        </div>
                    @endif
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
