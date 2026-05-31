<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Habit Tracker') — Espiritu Habit Tracker</title>
    <link rel="icon" type="image/png" href="{{ asset('images/HabitTrack.png') }}">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --pink-100: #FFF0F7;
            --pink-200: #FFE5F0;
            --pink-300: #FFD6E8;
            --pink-400: #FFB6D9;
            --pink-500: #FF8EC3;
            --pink-600: #E5709E;
            --text-dark: #3D2B3D;
            --text-mid: #6B4E6B;
            --text-light: #9B7E9B;
            --white: #FFFFFF;
            --error: #D94F4F;
            --success: #3A9E6F;
            --radius: 14px;
            --shadow: 0 8px 32px rgba(255, 182, 217, 0.25);
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--pink-200) 0%, var(--pink-100) 50%, #FDF0F8 100%);
            color: var(--text-dark);
        }

        /* Decorative blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }
        body::before {
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(255,182,217,0.35) 0%, transparent 70%);
            top: -100px; right: -100px;
        }
        body::after {
            width: 340px; height: 340px;
            background: radial-gradient(circle, rgba(255,214,232,0.4) 0%, transparent 70%);
            bottom: -80px; left: -80px;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 24px 16px;
        }

        /* Brand */
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 16px rgba(255,142,195,0.4);
        }
        .brand-icon i { font-size: 28px; color: var(--white); }
        .brand h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.3px;
        }
        .brand p {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 4px;
        }

        /* Card */
        .auth-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 36px 40px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255,182,217,0.2);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .card-subtitle {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 28px;
        }

        /* Form elements */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            font-size: 18px;
            color: var(--pink-400);
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            font-size: 14px;
            border: 1.5px solid var(--pink-300);
            border-radius: 10px;
            background: var(--pink-100);
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-input:focus {
            border-color: var(--pink-500);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(255,142,195,0.15);
        }
        .form-input.is-invalid {
            border-color: var(--error);
            background: #FFF5F5;
        }
        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(217,79,79,0.12);
        }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            font-size: 18px;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--pink-600); }

        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--error);
            margin-top: 6px;
        }
        .field-error i { font-size: 14px; }

        /* Submit button */
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            color: var(--white);
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.2px;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(255,142,195,0.4);
            margin-top: 6px;
        }
        .btn-primary:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,142,195,0.45);
        }
        .btn-primary:active { transform: translateY(0); }

        /* Divider */
        .divider {
            text-align: center;
            margin: 22px 0;
            position: relative;
            color: var(--text-light);
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 38%;
            height: 1px;
            background: var(--pink-300);
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }

        /* Auth link */
        .auth-link {
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
        }
        .auth-link a {
            color: var(--pink-600);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .auth-link a:hover { color: var(--pink-500); text-decoration: underline; }

        /* Toast container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--white);
            min-width: 280px;
            max-width: 380px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            pointer-events: all;
            animation: slideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            position: relative;
        }
        .toast.toast-success { background: linear-gradient(135deg, #3A9E6F, #2E8A5E); }
        .toast.toast-error   { background: linear-gradient(135deg, #D94F4F, #C03E3E); }

        .toast i { font-size: 20px; flex-shrink: 0; }
        .toast-close {
            margin-left: auto;
            background: none;
            border: none;
            color: rgba(255,255,255,0.75);
            cursor: pointer;
            font-size: 18px;
            padding: 0;
            flex-shrink: 0;
            transition: color 0.2s;
        }
        .toast-close:hover { color: white; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(60px); }
        }
        .toast.hiding { animation: slideOut 0.28s ease forwards; }

        /* Responsive */
        @media (max-width: 500px) {
            .auth-card { padding: 28px 22px; }
        }
    </style>
</head>
<body>

<!-- Toast notifications -->
<div class="toast-container" id="toastContainer">
    @if(session('toast_success'))
        <div class="toast toast-success" role="alert">
            <i class="ti ti-circle-check"></i>
            <span>{{ session('toast_success') }}</span>
            <button class="toast-close" onclick="dismissToast(this)"><i class="ti ti-x"></i></button>
        </div>
    @endif
    @if(session('toast_error'))
        <div class="toast toast-error" role="alert">
            <i class="ti ti-alert-circle"></i>
            <span>{{ session('toast_error') }}</span>
            <button class="toast-close" onclick="dismissToast(this)"><i class="ti ti-x"></i></button>
        </div>
    @endif
</div>

<div class="auth-wrapper">
    <!-- Brand -->
    <div class="brand">
        <img src="{{ asset('images/HabitTrack.png') }}" alt="Habit Tracker Logo"
             style="height:60px; width:auto; object-fit:contain; margin-bottom:12px;">
        <h1>Habit Tracker</h1>
        <p>Build better habits every day</p>
    </div>

    <!-- Auth card -->
    <div class="auth-card">
        @yield('content')
    </div>
</div>

<script>
    function dismissToast(btn) {
        const toast = btn.closest('.toast');
        toast.classList.add('hiding');
        toast.addEventListener('animationend', () => toast.remove());
    }

    // Auto-dismiss toasts after 4 seconds
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach(toast => {
            setTimeout(() => {
                if (toast.isConnected) dismissToast(toast.querySelector('.toast-close'));
            }, 4000);
        });
    });

    // Password visibility toggle
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            input.type = 'password';
            icon.className = 'ti ti-eye';
        }
    }
</script>

</body>
</html>
