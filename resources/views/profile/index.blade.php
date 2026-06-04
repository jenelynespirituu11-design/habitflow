@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        <!-- Profile Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center p-4">

                <!-- Profile picture -->
                @if ($user->profile_picture)
                    <img src="{{ $user->profile_picture_url }}"
                         alt="Profile"
                         class="rounded-circle mb-3"
                         style="width: 120px; height: 120px; object-fit: cover;
                                border: 3px solid #FFD6E8;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 120px; height: 120px; background-color: #FFE5F0;
                                font-size: 2.5rem; font-weight: 700; color: #FFB6D9;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h3 style="color: #4A4A4A;">{{ $user->name }}</h3>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted small">
                    Member since {{ $user->created_at->format('F j, Y') }}
                </p>

                <a href="/profile/edit" class="btn btn-primary">
                    <i class="ti ti-pencil"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Habit Stats -->
        <div class="row g-3">
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold" style="color: #ec4899;">{{ $totalHabits }}</div>
                        <div class="small text-muted">Total</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold" style="color: #16a34a;">{{ $activeHabits }}</div>
                        <div class="small text-muted">Active</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold" style="color: #9ca3af;">{{ $pausedHabits }}</div>
                        <div class="small text-muted">Paused</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
