@extends('layouts.app')

@push('styles')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 130px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .auth-card {
        width: 100%;
        max-width: 450px;
        background: #fff;
        border: 1px solid var(--pink-200);
        border-radius: 18px;
        box-shadow: 0 8px 40px rgba(255, 143, 179, 0.14), 0 2px 8px rgba(0,0,0,0.04);
        padding: 2.5rem 2.25rem;
    }

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

    .auth-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-mid);
        margin-bottom: 0.5rem;
        display: block;
        letter-spacing: 0.01em;
    }

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
    .auth-input.is-invalid { border-color: #dc3545; }
    .auth-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }

    .auth-group { margin-bottom: 1.25rem; }
    .auth-group:last-of-type { margin-bottom: 1.75rem; }

    .auth-btn {
        width: 100%;
        height: 50px;
        background: linear-gradient(135deg, #FFB6D9, #FF8FB3);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: opacity 0.18s, transform 0.15s, box-shadow 0.18s;
        box-shadow: 0 4px 16px rgba(255, 143, 179, 0.38);
    }
    .auth-btn:hover {
        opacity: 0.93;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 143, 179, 0.48);
    }
    .auth-btn:active { transform: translateY(0); }

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

    .field-error {
        font-size: 0.8rem;
        color: #dc3545;
        margin-top: 0.35rem;
    }

    /* Password strength hint */
    .password-hint {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <!-- Icon + Heading -->
        <div class="auth-icon">
            <i class="ti ti-user-plus"></i>
        </div>
        <h1 class="auth-heading">Create Account</h1>
        <p class="auth-subheading">Start building better habits today</p>

        <form action="/register" method="POST" novalidate>
            @csrf

            <!-- Full Name -->
            <div class="auth-group">
                <label class="auth-label" for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       class="auth-input @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Your full name"
                       autocomplete="name"
                       required>
                @error('name')
                    <div class="field-error"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

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
                       placeholder="At least 8 characters"
                       autocomplete="new-password"
                       required>
                <div class="password-hint">Use at least 8 characters</div>
                @error('password')
                    <div class="field-error"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="auth-group">
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="auth-input"
                       placeholder="Repeat your password"
                       autocomplete="new-password"
                       required>
            </div>

            <button type="submit" class="auth-btn">
                Create Account
            </button>
        </form>

        <p class="auth-footer-text">
            Already have an account?
            <a href="/login" class="auth-link">Sign in here</a>
        </p>

    </div>
</div>
@endsection
