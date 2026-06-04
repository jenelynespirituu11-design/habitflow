@extends('layouts.app')

@section('page-title', $habit->name)

@section('content')
<div class="mb-3">
    <a href="/habits" class="btn btn-outline-secondary btn-sm">
        <i class="ti ti-arrow-left"></i> Back to Habits
    </a>
</div>

<div class="row mb-4">
    <!-- Habit Info -->
    <div class="col-md-8">
        <div class="card h-100" style="border-left: 5px solid {{ $habit->color }};">
            <div class="card-body">
                <h2 style="color: #4A4A4A;">
                    <i class="ti ti-{{ $habit->icon }}" style="color: {{ $habit->color }}; font-size: 1.8rem;"></i>
                    {{ $habit->name }}
                </h2>
                @if ($habit->description)
                    <p class="text-muted">{{ $habit->description }}</p>
                @endif
                <p><strong>Category:</strong> {{ ucfirst($habit->category ?? '—') }}</p>
                <p><strong>Frequency:</strong> {{ ucfirst($habit->frequency) }} · {{ $habit->target_days }} day(s)/week</p>
                <p><strong>Started:</strong> {{ $habit->start_date->format('M d, Y') }}</p>

                <!-- Log today -->
                @php
                    $loggedToday = $habit->logs()
                        ->whereDate('logged_date', today())
                        ->where('completed', true)
                        ->exists();
                @endphp
                @if (! $loggedToday)
                    <form action="/habits/{{ $habit->id }}/log" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check"></i> Log Today
                        </button>
                    </form>
                @else
                    <button class="btn" disabled
                            style="background-color: #6BCB77; color: #fff; border: none;">
                        <i class="ti ti-circle-check"></i> Logged Today
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="col-md-4">
        <div class="card text-center mb-3">
            <div class="card-body">
                <div class="fs-2 fw-bold" style="color: #FFB6D9;">{{ $totalCompleted }}</div>
                <div class="text-muted small">Total Completed</div>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <div class="fs-2 fw-bold" style="color: #FFB6D9;">{{ $currentStreak }}</div>
                <div class="text-muted small">Current Streak</div>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <div class="fs-2 fw-bold" style="color: #FFB6D9;">{{ $longestStreak }}</div>
                <div class="text-muted small">Longest Streak</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-2 fw-bold" style="color: #FFB6D9;">{{ $completionRate }}%</div>
                <div class="text-muted small">Completion Rate</div>
            </div>
        </div>
    </div>
</div>

<!-- Log History -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title" style="color: #4A4A4A;">Log History (Last 30 days)</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($habit->logs()->orderBy('logged_date', 'desc')->limit(30)->get() as $log)
                    <tr>
                        <td>{{ $log->logged_date->format('M d, Y') }}</td>
                        <td>
                            @if ($log->completed)
                                <span style="color: #6BCB77;"><i class="ti ti-circle-check"></i> Completed</span>
                            @else
                                <span style="color: #FF6B6B;"><i class="ti ti-circle-x"></i> Missed</span>
                            @endif
                        </td>
                        <td>{{ $log->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit / Delete -->
<div class="d-flex gap-2">
    <a href="/habits/{{ $habit->id }}/edit" class="btn btn-primary">
        <i class="ti ti-pencil"></i> Edit Habit
    </a>
    <form action="/habits/{{ $habit->id }}" method="POST"
          onsubmit="return confirm('Delete this habit and all its logs?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="ti ti-trash"></i> Delete Habit
        </button>
    </form>
</div>
@endsection
