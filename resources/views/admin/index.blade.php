@extends('layouts.app')

@section('page-title', 'Admin Panel')

@section('content')

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:11px;color:#bbb;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Total Users</div>
                <div style="font-size:36px;font-weight:700;color:#FFB6D9;">{{ $totalUsers }}</div>
                <div style="font-size:12px;color:#ccc;margin-top:4px;">registered accounts</div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <div style="padding:20px 24px 16px;border-bottom:1px solid #FFE5F0;">
            <h6 style="font-weight:700;color:#333;margin:0;">All Users</h6>
        </div>

        @if ($users->isEmpty())
            <div style="text-align:center;padding:40px;color:#bbb;font-size:14px;">
                No registered users yet.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Habits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $i => $user)
                    <tr>
                        <td style="color:#bbb;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;
                                            background:#FFE5F0;display:flex;align-items:center;
                                            justify-content:center;font-weight:700;
                                            color:#FF8FB3;font-size:14px;flex-shrink:0;overflow:hidden;">
                                    @if ($user->profile_picture)
                                        <img src="{{ $user->profile_picture_url }}"
                                             style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span style="font-weight:600;color:#333;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color:#888;">{{ $user->email }}</td>
                        <td style="color:#aaa;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="color:#888;">{{ $user->habits()->count() }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="/admin/users/{{ $user->id }}/edit"
                                   class="btn btn-secondary btn-sm">
                                    <i class="ti ti-pencil me-1"></i>Edit
                                </a>
                                <form action="/admin/users/{{ $user->id }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="padding:5px 12px;background:transparent;
                                                   border:1px solid #FFD6E8;color:#FF6B6B;border-radius:8px;">
                                        <i class="ti ti-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
