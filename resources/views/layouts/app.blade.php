<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Create Birthday Card') – Wishing Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #c73659;
            --primary-dark: #a82d4a;
            --primary-light: #e8a4b8;
            --accent: #f4a261;
            --bg: #fefbf7;
            --bg-subtle: #f8f4ef;
            --card-bg: #ffffff;
            --text: #1f1d1b;
            --text-muted: #6b6560;
            --border: rgba(0,0,0,0.06);
            --radius: 20px;
            --radius-sm: 12px;
            --shadow: 0 4px 24px rgba(0,0,0,0.06);
            --shadow-hover: 0 20px 50px rgba(0,0,0,0.12);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; line-height: 1.6; -webkit-font-smoothing: antialiased; }
        .site-header { background: var(--card-bg); border-bottom: 1px solid var(--border); padding: 0.75rem 0; position: sticky; top: 0; z-index: 100; }
        .site-header .navbar-brand { font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.35rem; color: var(--primary); letter-spacing: -0.02em; }
        .site-header .navbar-brand:hover { color: var(--primary-dark); }
        .btn-create, .btn-back { padding: 0.5rem 1.25rem; border-radius: 999px; font-weight: 600; font-size: 0.9375rem; transition: transform 0.2s, box-shadow 0.2s; }
        .btn-create { background: var(--primary); border: none; color: #fff; }
        .btn-create:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(199, 54, 89, 0.35); }
        .btn-back { background: var(--bg-subtle); border: 1px solid var(--border); color: var(--text); }
        .btn-back:hover { background: #fff; color: var(--text); border-color: var(--primary-light); }
        main { padding: 0; min-height: calc(100vh - 56px); }
        .container { max-width: 1400px; }
        .nav-categories-btn { background: none; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0.4rem 0.75rem; font-size: 0.9rem; color: var(--text); display: flex; align-items: center; gap: 0.4rem; }
        .nav-categories-btn:hover { border-color: var(--primary-light); color: var(--primary); }
        .nav-categories-btn i { font-size: 0.85em; }
        @yield('styles')
    </style>
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
    @yield('scripts')
</body>
</html>
