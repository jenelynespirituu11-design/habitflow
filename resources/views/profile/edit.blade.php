@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title mb-4" style="color: #FFB6D9;">
                    <i class="ti ti-user"></i> Edit Profile
                </h2>

                <!-- Current picture -->
                <div class="text-center mb-3">
                    @if ($user->profile_picture)
                        <img src="{{ $user->profile_picture_url }}"
                             alt="Profile"
                             class="rounded-circle"
                             style="width: 80px; height: 80px; object-fit: cover;
                                    border: 3px solid #FFD6E8;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background-color: #FFE5F0;
                                    font-size: 2rem; font-weight: 700; color: #FFB6D9;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <form action="/profile" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Profile Picture
                            <small class="text-muted">(jpg, png, gif — max 2MB)</small>
                        </label>
                        <input type="file" name="profile_picture"
                               class="form-control @error('profile_picture') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/gif">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="/profile" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
