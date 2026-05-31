<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HabitFlow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-pink: #FFB6D9;
            --dark-pink:    #FF8FB3;
            --light-pink:   #FFE5F0;
            --pale-pink:    #FFD6E8;
        }

        body { background-color: #fff; }

        /* Navbar */
        .navbar {
            background-color: #fff;
            border-bottom: 2px solid var(--pale-pink);
        }
        .navbar-brand {
            color: var(--primary-pink) !important;
            font-weight: 700;
            font-size: 1.4rem;
        }
        .nav-link { color: #4A4A4A !important; }
        .nav-link.active {
            color: var(--dark-pink) !important;
            border-bottom: 2px solid var(--dark-pink);
        }
        .nav-link:hover { color: var(--dark-pink) !important; }

        /* Cards */
        .card { border: 1px solid var(--pale-pink); }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-pink);
            border-color: var(--primary-pink);
        }
        .btn-primary:hover {
            background-color: var(--dark-pink);
            border-color: var(--dark-pink);
        }

        /* Footer */
        footer {
            background-color: var(--light-pink);
            color: #4A4A4A;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="/dashboard">
            <i class="ti ti-heart"></i> HabitFlow
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                           href="/dashboard">
                            <i class="ti ti-layout-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('habits*') ? 'active' : '' }}"
                           href="/habits">
                            <i class="ti ti-star"></i> Habits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"
                           href="/profile">
                            <i class="ti ti-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0 ms-2"
                                    style="color: #4A4A4A !important;">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-dismissible fade show"
             style="background-color: var(--light-pink); border: 1px solid var(--pale-pink); color: #4A4A4A;">
            <i class="ti ti-circle-check me-1" style="color: #6BCB77;"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach ($errors->all() as $error)
                <div><i class="ti ti-alert-circle me-1"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- Page Content -->
<div class="container my-4">
    @yield('content')
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
    <small>© {{ date('Y') }} HabitFlow — Build better habits, one day at a time.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
