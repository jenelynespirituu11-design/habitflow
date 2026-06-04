@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')

<nav style="font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:6px;color:#bbb;">
    <a href="/admin" style="color:#FFB6D9;text-decoration:none;font-weight:500;">Admin Panel</a>
    <i class="ti ti-chevron-right" style="font-size:12px;"></i>
    <span style="color:#888;">Edit User</span>
</nav>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#FFE5F0;
                                display:flex;align-items:center;justify-content:center;
                                font-weight:700;font-size:18px;color:#FF8FB3;overflow:hidden;flex-shrink:0;">
                        @if ($user->profile_picture)
                            <img src="{{ $user->profile_picture_url }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div style="font-weight:700;color:#333;">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#aaa;">{{ $user->email }}</div>
                    </div>
                </div>

                <form action="/admin/users/{{ $user->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name <span style="color:#FF6B6B;">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address <span style="color:#FF6B6B;">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password
                            <small style="color:#bbb;font-weight:400;">(leave blank to keep current)</small>
                        </label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min 8 characters">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" placeholder="Repeat new password">
                    </div>

                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="/admin" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
