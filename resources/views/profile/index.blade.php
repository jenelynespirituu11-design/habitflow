@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">

    <!-- Left: Profile Info Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3"
                     style="width:100px;height:100px;font-size:2.5rem;">
                    @if ($user->profile_picture)
                        <img src="{{ $user->profile_picture_url }}" alt="Profile">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>

                <h5 style="font-weight:700;margin-bottom:4px;">{{ $user->name }}</h5>
                <p style="color:#aaa;font-size:13px;margin-bottom:4px;">{{ $user->email }}</p>
                <p style="color:#ccc;font-size:12px;margin-bottom:24px;">
                    Member since {{ $user->created_at->format('M d, Y') }}
                </p>

                <div style="border-top:1px solid #FFE5F0;padding-top:20px;">
                    <div class="row g-0 text-center">
                        <div class="col-4">
                            <div style="font-size:22px;font-weight:700;color:#FFB6D9;">{{ $totalHabits }}</div>
                            <div style="font-size:11px;color:#bbb;text-transform:uppercase;">Total</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:22px;font-weight:700;color:#6BCB77;">{{ $activeHabits }}</div>
                            <div style="font-size:11px;color:#bbb;text-transform:uppercase;">Active</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:22px;font-weight:700;color:#aaa;">{{ $pausedHabits }}</div>
                            <div style="font-size:11px;color:#bbb;text-transform:uppercase;">Paused</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Edit Forms -->
    <div class="col-md-8">

        <!-- Edit Profile -->
        <div class="card">
            <div class="card-body">
                <h6 style="font-weight:700;margin-bottom:20px;color:#333;">Edit Profile</h6>

                <form action="/profile" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Profile Picture
                            <small style="color:#bbb;font-weight:400;">(jpg, png, gif — max 2 MB)</small>
                        </label>
                        <input type="file" name="profile_picture"
                               class="form-control @error('profile_picture') is-invalid @enderror"
                               accept="image/*">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name <span style="color:#FF6B6B;">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email Address <span style="color:#FF6B6B;">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-body">
                <h6 style="font-weight:700;margin-bottom:20px;color:#333;">Change Password</h6>

                <form action="/profile/password" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 8 characters" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat new password" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
