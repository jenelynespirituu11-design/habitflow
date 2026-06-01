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
            --pink-100:  #FFE5F0;
            --pink-200:  #FFD6E8;
            --pink-400:  #FFB6D9;
            --pink-500:  #FF8FB3;
            --pink-600:  #E5709E;
            --text-dark: #1A1A2E;
            --text-mid:  #4A4A6A;
            --text-muted:#8888AA;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: #fdf2f8;
            color: var(--text-dark);
        }
    </style>

    @stack('styles')
</head>
<body>

    @if (session('success'))
        <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                    background:#fff;border:1px solid var(--pink-200);
                    border-radius:12px;padding:.75rem 1.25rem;
                    box-shadow:0 4px 20px rgba(255,143,179,.2);
                    font-size:.875rem;color:var(--text-mid);max-width:320px;">
            <i class="ti ti-circle-check me-2" style="color:#6BCB77;"></i>
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
