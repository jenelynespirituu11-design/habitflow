<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HabitFlow</title>
    <link rel="icon" type="image/png" href="/images/HabitTrack.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --pink-50:   #FFF0F7;
            --pink-100:  #FFE5F0;
            --pink-200:  #FFD6E8;
            --pink-400:  #FFB6D9;
            --pink-500:  #FF8FB3;
            --pink-600:  #E5709E;
            --text-dark: #1A1A2E;
            --text-mid:  #4A4A6A;
            --text-muted:#8888AA;
            --radius:    12px;
            --shadow:    0 4px 24px rgba(255,143,179,0.12);
        }

        * { box-sizing: border-box; }

        /* Full-height layout for sticky footer */
        html, body { height: 100%; }
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background-color: #f9fafb;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Consistent page container */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.25rem;
            width: 100%;
        }
        @media (max-width: 576px) {
            .page-container { padding: 1.25rem 1rem; }
        }

        /* Push footer to bottom */
        .page-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .page-content {
            flex: 1;
        }

        /* ── Navbar ─────────────────────────────── */
        .navbar {
            background-color: #fff;
            border-bottom: 2px solid var(--pink-200);
            padding: 0.75rem 0;
        }
        .navbar-brand {
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .navbar-brand img { height: 36px; width: auto; }
        .navbar-brand:hover { opacity: 0.85; }

        .nav-link {
            color: var(--text-mid) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 0.75rem !important;
            border-radius: 8px;
            transition: background 0.18s, color 0.18s;
        }
        .nav-link:hover {
            color: var(--pink-600) !important;
            background-color: var(--pink-50);
        }
        .nav-link.active {
            color: var(--pink-600) !important;
            background-color: var(--pink-100);
            font-weight: 600;
        }

        /* Logout button styled as nav link */
        .nav-logout {
            background: none;
            border: none;
            color: var(--text-mid) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            transition: background 0.18s, color 0.18s;
            cursor: pointer;
        }
        .nav-logout:hover {
            color: var(--pink-600) !important;
            background-color: var(--pink-50);
        }

        /* ── Cards (global) ─────────────────────── */
        .card {
            border: 1px solid var(--pink-200);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        /* ── Buttons (global) ───────────────────── */
        .btn-primary {
            background: #ec4899;
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
            box-shadow: 0 2px 8px rgba(236,72,153,0.25);
        }
        .btn-primary:hover {
            background: #db2777;
            border: none;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(236,72,153,0.35);
        }
        .btn-primary:active { transform: translateY(0); background: #be185d; }

        /* ── Flash alerts ───────────────────────── */
        .flash-success {
            background-color: var(--pink-100);
            border: 1px solid var(--pink-200);
            color: var(--text-mid);
            border-radius: 10px;
        }

        /* ── Footer ─────────────────────────────── */
        footer {
            background-color: var(--pink-100);
            color: var(--text-muted);
            font-size: 0.82rem;
            padding: 1rem 0;
            border-top: 1px solid var(--pink-200);
            text-align: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="page-wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <img src="/images/HabitTrack.png" alt="HabitTrack">
            </a>
            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                               href="/dashboard">
                                <i class="ti ti-layout-dashboard me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('habits*') ? 'active' : '' }}"
                               href="/habits">
                                <i class="ti ti-star me-1"></i>Habits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"
                               href="/profile">
                                <i class="ti ti-user me-1"></i>Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="/logout" method="POST" class="d-inline m-0 p-0">
                                @csrf
                                <button type="submit" class="nav-logout">
                                    <i class="ti ti-logout me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                               href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
                               href="/register">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div style="max-width:1200px;margin:0 auto;padding:1rem 1.25rem 0;width:100%;">
        @if (session('success'))
            <div class="alert flash-success alert-dismissible fade show" role="alert">
                <i class="ti ti-circle-check me-2" style="color: #6BCB77;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div><i class="ti ti-point me-1"></i>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <div class="page-content">
        <div class="page-container">
            @yield('content')
        </div>
    </div>

</div><!-- /page-wrapper -->

<!-- Sticky Footer -->
<footer>
    © {{ date('Y') }} HabitFlow &mdash; Build better habits, one day at a time.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
