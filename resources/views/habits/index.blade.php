@extends('layouts.app')

@section('page-title', 'My Habits')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <span style="font-size:14px;color:#888;">{{ $habits->count() }} habit{{ $habits->count() !== 1 ? 's' : '' }}</span>
    <a href="/habits/create" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i>Add New Habit
    </a>
</div>

@if ($habits->isEmpty())
    <div class="card">
        <div class="card-body text-center" style="padding:60px 24px;">
            <h6 style="color:#333;margin-bottom:8px;">No habits yet</h6>
            <p style="color:#bbb;font-size:14px;margin-bottom:20px;">Start building your routine by adding your first habit.</p>
            <a href="/habits/create" class="btn btn-primary">Create First Habit</a>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body" style="padding:0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Habit</th>
                        <th>Category</th>
                        <th>Frequency</th>
                        <th>This Week</th>
                        <th>Streak</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($habits as $habit)
                    @php
                        $logsThisWeek = $habit->logs()
                            ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
                            ->where('completed', true)->count();
                        $loggedToday = $habit->logs()
                            ->whereDate('logged_date', today())
                            ->where('completed', true)->exists();
                        $streak = 0;
                        $streakDate = now()->copy();
                        while ($habit->logs()->whereDate('logged_date', $streakDate)->where('completed', true)->exists()) {
                            $streak++;
                            $streakDate->subDay();
                        }
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;border-radius:50%;background:{{ $habit->color }};display:inline-block;flex-shrink:0;"></span>
                                <strong style="color:#333;">{{ $habit->name }}</strong>
                            </div>
                        </td>
                        <td>{{ ucfirst($habit->category ?? '—') }}</td>
                        <td>{{ ucfirst($habit->frequency) }}</td>
                        <td style="color:#888;">{{ $logsThisWeek }}/{{ $habit->target_days }}</td>
                        <td style="color:#888;">{{ $streak }} day{{ $streak !== 1 ? 's' : '' }}</td>
                        <td>
                            <span style="padding:3px 10px;background:#FFE5F0;color:#FF8FB3;border-radius:20px;font-size:12px;font-weight:600;">
                                {{ ucfirst($habit->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;">
                                @if (!$loggedToday)
                                    <form action="/habits/{{ $habit->id }}/log" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Log</button>
                                    </form>
                                @else
                                    <span style="font-size:12px;color:#6BCB77;font-weight:600;">✓ Logged</span>
                                @endif
                                <a href="/habits/{{ $habit->id }}/edit" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="/habits/{{ $habit->id }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this habit and all its logs?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="padding:5px 12px;font-size:12px;background:transparent;border:1px solid #FFD6E8;color:#FF6B6B;border-radius:8px;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
