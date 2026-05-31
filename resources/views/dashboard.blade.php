<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — Espiritu Habit Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --pink-100: #FFF0F7; --pink-200: #FFE5F0; --pink-300: #FFD6E8;
            --pink-400: #FFB6D9; --pink-500: #FF8EC3; --pink-600: #E5709E;
            --text-dark: #3D2B3D; --text-mid: #6B4E6B; --text-light: #9B7E9B;
            --white: #FFFFFF; --success: #3A9E6F;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--pink-200) 0%, var(--pink-100) 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: var(--text-dark);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 24px;
        }
        .card {
            background: var(--white);
            border-radius: 16px;
            padding: 40px 48px;
            box-shadow: 0 8px 32px rgba(255,182,217,0.25);
            border: 1px solid rgba(255,182,217,0.2);
            text-align: center;
            max-width: 480px; width: 100%;
        }
        .avatar {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 16px rgba(255,142,195,0.4);
        }
        .avatar i { font-size: 32px; color: var(--white); }
        h1 { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        p { font-size: 14px; color: var(--text-light); margin-bottom: 28px; line-height: 1.6; }
        form button {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            color: var(--white);
            border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(255,142,195,0.35);
            transition: opacity 0.2s, transform 0.15s;
        }
        form button:hover { opacity: 0.9; transform: translateY(-1px); }

        .toast-container {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
        }
        .toast {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-radius: 12px;
            font-size: 14px; font-weight: 500; color: var(--white);
            min-width: 280px; max-width: 380px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            animation: slideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .toast.toast-success { background: linear-gradient(135deg, #3A9E6F, #2E8A5E); }
        .toast i { font-size: 20px; }
        .toast-close { margin-left: auto; background: none; border: none; color: rgba(255,255,255,0.75); cursor: pointer; font-size: 18px; padding: 0; }
        @keyframes slideIn { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(60px); } }
        .toast.hiding { animation: slideOut 0.28s ease forwards; }
    </style>
</head>
<body>

<div class="toast-container">
    @if(session('toast_success'))
        <div class="toast toast-success">
            <i class="ti ti-circle-check"></i>
            <span>{{ session('toast_success') }}</span>
            <button class="toast-close" onclick="dismissToast(this)"><i class="ti ti-x"></i></button>
        </div>
    @endif
</div>

<div class="card">
    <div class="avatar">
        <i class="ti ti-chart-bar"></i>
    </div>
    <h1>Hello, {{ Auth::user()->full_name }}!</h1>
    <p>You are logged in. The full dashboard is coming soon — habits, streaks, and progress charts will live here.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">
            <i class="ti ti-logout"></i>
            Sign Out
        </button>
    </form>
</div>

<script>
    function dismissToast(btn) {
        const toast = btn.closest('.toast');
        toast.classList.add('hiding');
        toast.addEventListener('animationend', () => toast.remove());
    }
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach(t => {
            setTimeout(() => { if (t.isConnected) dismissToast(t.querySelector('.toast-close')); }, 4000);
        });
    });
</script>
</body>
</html>
