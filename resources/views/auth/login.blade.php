@extends('layouts.auth')

@push('styles')
<style>
    /* Center card vertically and horizontally */
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .auth-card {
        width: 100%;
        max-width: 360px;
        background: #fff;
        border: 1px solid var(--pink-200);
        border-radius: 14px;
        box-shadow: 0 8px 40px rgba(255, 143, 179, 0.14), 0 2px 8px rgba(0,0,0,0.04);
        padding: 1.75rem 1.75rem;
    }

    /* Heading */
    .auth-heading {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 0.35rem;
        letter-spacing: -0.02em;
    }
    .auth-subheading {
        text-align: center;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    /* Form labels */
    .auth-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-mid);
        margin-bottom: 0.5rem;
        display: block;
        letter-spacing: 0.01em;
    }

    /* Inputs */
    .auth-input {
        width: 100%;
        height: 48px;
        padding: 0 1rem;
        border: 1.5px solid var(--pink-200);
        border-radius: 10px;
        font-size: 0.95rem;
        color: var(--text-dark);
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .auth-input::placeholder { color: #BBBBCC; }
    .auth-input:focus {
        border-color: var(--pink-400);
        box-shadow: 0 0 0 3.5px rgba(255, 182, 217, 0.22);
    }
    .auth-input.is-invalid {
        border-color: #dc3545;
    }
    .auth-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }

    /* Form groups */
    .auth-group { margin-bottom: 1.25rem; }
    .auth-group:last-of-type { margin-bottom: 1.75rem; }

    /* Submit button */
    .auth-btn {
        width: 100%;
        height: 50px;
        background: #ec4899;
        border: none;
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
        box-shadow: 0 4px 16px rgba(236, 72, 153, 0.28);
    }
    .auth-btn:hover {
        background: #db2777;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(236, 72, 153, 0.38);
    }
    .auth-btn:active { transform: translateY(0); }

    /* Bottom link */
    .auth-footer-text {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.88rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    .auth-link {
        color: var(--pink-500);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s;
    }
    .auth-link:hover { color: var(--pink-600); text-decoration: underline; }

    /* Icon accent */
    .auth-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        background: var(--pink-100);
        border-radius: 50%;
        margin: 0 auto 1.25rem;
    }
    .auth-icon i { font-size: 1.6rem; color: var(--pink-500); }

    /* Invalid feedback */
    .field-error {
        font-size: 0.8rem;
        color: #dc3545;
        margin-top: 0.35rem;
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <!-- Logo -->
        <div style="text-align:center;margin-bottom:1.25rem;">
            <img src="/images/HabitTrack.png" alt="HabitTrack" style="height:72px;width:auto;">
        </div>
        <h1 class="auth-heading">Welcome Back</h1>
        <p class="auth-subheading">Sign in to continue your habit journey</p>

        <form action="/login" method="POST" novalidate>
            @csrf

            <!-- Email -->
            <div class="auth-group">
                <label class="auth-label" for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       class="auth-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       autocomplete="email"
                       required>
                @error('email')
                    <div class="field-error"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="auth-group">
                <label class="auth-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="auth-input @error('password') is-invalid @enderror"
                       placeholder="Your password"
                       autocomplete="current-password"
                       required>
                @error('password')
                    <div class="field-error"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="auth-btn">
                Sign In
            </button>
        </form>

        <p class="auth-footer-text">
            Don't have an account?
            <a href="/register" class="auth-link">Create one here</a>
        </p>

    </div>
</div>
@endsection
