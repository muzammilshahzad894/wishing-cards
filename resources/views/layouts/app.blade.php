<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Create Birthday Card') – Wishing Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #c73659;
            --primary-dark: #a82d4a;
            --accent: #f4a261;
            --bg: #fefbf7;
            --card-bg: #fff;
            --text: #2d2a26;
            --text-muted: #6b6560;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        .site-header { background: var(--card-bg); box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 1rem 0; }
        .site-header .navbar-brand { font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.5rem; color: var(--primary); }
        .site-header .navbar-brand:hover { color: var(--primary-dark); }
        .btn-create { background: var(--primary); border: none; color: #fff; padding: 0.5rem 1.25rem; border-radius: 999px; font-weight: 600; }
        .btn-create:hover { background: var(--primary-dark); color: #fff; }
        main { padding: 2rem 0 4rem; }
        @yield('styles')
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <nav class="d-flex align-items-center justify-content-between">
                <a class="navbar-brand" href="{{ url('/') }}">Wishing Cards</a>
                <div class="d-flex align-items-center gap-2">
                    @yield('header-right')
                </div>
            </nav>
        </div>
    </header>
    <main class="container">
        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
