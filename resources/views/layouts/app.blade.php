<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HabitFlow</title>
    <link rel="icon" type="image/png" href="/images/HabitTrack.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #FFFFFF;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
        }

        /* ── Sidebar ─────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            width: 260px;
            height: 100vh;
            background-color: #FFFFFF;
            border-right: 1px solid #FFD6E8;
            padding: 24px 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            padding: 0 24px;
            margin-bottom: 32px;
        }
        .sidebar-logo img { height: 38px; width: auto; }

        .sidebar-section { margin-bottom: 24px; }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            padding: 0 24px;
            margin-bottom: 6px;
            letter-spacing: 0.8px;
        }

        .sidebar-menu { list-style: none; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            border-right: 3px solid transparent;
        }
        .sidebar-menu a:hover {
            background-color: #FFF0F7;
            color: #FF8FB3;
        }
        .sidebar-menu a.active {
            background-color: #FFE5F0;
            color: #FF8FB3;
            border-right-color: #FFB6D9;
            font-weight: 600;
        }
        .sidebar-menu a i { font-size: 16px; }

        .sidebar-bottom {
            margin-top: auto;
            padding: 0 16px;
        }
        .sidebar-bottom form { margin: 0; }
        .sidebar-bottom button {
            width: 100%;
            padding: 10px;
            background-color: transparent;
            border: 1px solid #FFD6E8;
            color: #888;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-bottom button:hover {
            background-color: #FFE5F0;
            color: #FF8FB3;
        }

        /* ── Main Content ─────────────────────────────────────────── */
        .main-content {
            margin-left: 260px;
            padding: 36px 40px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid #FFE5F0;
        }
        .page-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #222;
        }

        .user-meta { text-align: right; }
        .user-meta .user-name { font-size: 14px; font-weight: 600; color: #333; }
        .user-meta .user-email { font-size: 12px; color: #aaa; }

        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background-color: #FFE5F0;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
            color: #FF8FB3;
            border: 2px solid #FFD6E8;
            overflow: hidden;
            flex-shrink: 0;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

        /* ── Alerts ───────────────────────────────────────────────── */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger  { background-color: #f8d7da; color: #721c24; }

        /* ── Cards ────────────────────────────────────────────────── */
        .card {
            border: 1px solid #FFD6E8;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(255,182,217,0.10);
            margin-bottom: 20px;
        }
        .card-body { padding: 24px; }

        /* ── Buttons ──────────────────────────────────────────────── */
        .btn-primary {
            background-color: #FFB6D9;
            border-color: #FFB6D9;
            color: #fff;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            transition: background 0.15s, border-color 0.15s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #FF8FB3;
            border-color: #FF8FB3;
            color: #fff;
        }
        .btn-secondary, .btn-outline-secondary {
            background-color: transparent;
            border: 1px solid #FFD6E8;
            color: #666;
            font-weight: 500;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            transition: background 0.15s, color 0.15s;
        }
        .btn-secondary:hover, .btn-outline-secondary:hover {
            background-color: #FFE5F0;
            border-color: #FFB6D9;
            color: #FF8FB3;
        }
        .btn-danger {
            background-color: #FF6B6B;
            border-color: #FF6B6B;
            color: #fff;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn-danger:hover { background-color: #e55; border-color: #e55; color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ── Forms ────────────────────────────────────────────────── */
        .form-control, .form-select {
            border: 1px solid #FFD6E8;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            color: #333;
        }
        .form-control:focus, .form-select:focus {
            border-color: #FFB6D9;
            box-shadow: 0 0 0 3px rgba(255,182,217,0.20);
        }
        .form-label {
            font-weight: 600;
            color: #444;
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* ── Tables ───────────────────────────────────────────────── */
        .table { font-size: 14px; margin: 0; }
        .table thead th {
            background-color: #FFF0F7;
            border-bottom: 1px solid #FFD6E8;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #888;
            padding: 14px 16px;
        }
        .table tbody td {
            border-bottom: 1px solid #FFF0F7;
            padding: 14px 16px;
            color: #555;
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background-color: #FFFAFC; }

        /* ── Progress ─────────────────────────────────────────────── */
        .progress { background-color: #FFE5F0; }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; padding: 16px 0; }
            .main-content { margin-left: 0; padding: 20px; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <a href="/dashboard"><img src="/images/HabitTrack.png" alt="HabitFlow"></a>
        </div>

        @auth
            <div class="sidebar-section">
                <div class="sidebar-section-title">Main</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="ti ti-layout-dashboard"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Habits</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/habits" class="{{ request()->is('habits*') ? 'active' : '' }}">
                            <i class="ti ti-star"></i> My Habits
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Account</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/profile" class="{{ request()->is('profile*') ? 'active' : '' }}">
                            <i class="ti ti-user"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-bottom">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit"><i class="ti ti-logout me-2"></i>Logout</button>
                </form>
            </div>
        @endauth
    </div>

    <!-- Main Content -->
    <div class="main-content">

        @auth
        <div class="page-header">
            <h1>@yield('page-title', 'HabitFlow')</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-meta">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
                <div class="user-avatar">
                    @if (auth()->user()->profile_picture)
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
            </div>
        </div>
        @endauth

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
