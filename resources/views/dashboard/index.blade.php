@extends('layouts.app')

@section('content')
<h2 class="mb-4" style="color: #4A4A4A;">
    Welcome, {{ auth()->user()->name }}! <i class="ti ti-heart" style="color: #FFB6D9;"></i>
</h2>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="fs-1 fw-bold" style="color: #FFB6D9;">{{ $todayCompleted }}</div>
                <div class="small text-muted">Today's Completed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="fs-1 fw-bold" style="color: #FFB6D9;">{{ $activeHabits }}</div>
                <div class="small text-muted">Active Habits</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="fs-1 fw-bold" style="color: #FFB6D9;">{{ $weekCompleted }}</div>
                <div class="small text-muted">This Week</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="fs-1 fw-bold" style="color: #FFB6D9;">{{ $currentStreak }}</div>
                <div class="small text-muted">Day Streak</div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Chart -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title" style="color: #4A4A4A;">Weekly Completions</h5>
        <canvas id="weeklyChart" height="100"></canvas>
    </div>
</div>

<!-- Recent Habits Table -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0" style="color: #4A4A4A;">Active Habits</h5>
            <a href="/habits" class="btn btn-sm btn-primary">View All</a>
        </div>

        @if ($recentHabits->isEmpty())
            <p class="text-muted">No active habits yet.
                <a href="/habits/create" style="color: #FFB6D9;">Create your first habit!</a>
            </p>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Habit</th>
                        <th>Category</th>
                        <th>Today</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentHabits as $habit)
                    @php
                        $logged = \App\Models\HabitLog::where('habit_id', $habit->id)
                            ->whereDate('logged_date', today())
                            ->where('completed', true)
                            ->exists();
                    @endphp
                    <tr>
                        <td>
                            <i class="ti ti-{{ $habit->icon }}" style="color: {{ $habit->color }};"></i>
                            {{ $habit->name }}
                        </td>
                        <td>{{ ucfirst($habit->category ?? '—') }}</td>
                        <td>
                            @if ($logged)
                                <span style="color: #6BCB77;"><i class="ti ti-circle-check"></i> Done</span>
                            @else
                                <span style="color: #FF6B6B;"><i class="ti ti-circle-x"></i> Pending</span>
                            @endif
                        </td>
                        <td>
                            @if (! $logged)
                                <form action="/habits/{{ $habit->id }}/log" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Log
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('weeklyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['days']) !!},
        datasets: [{
            label: 'Habits Completed',
            data:  {!! json_encode($chartData['completed']) !!},
            borderColor: '#FFB6D9',
            backgroundColor: 'rgba(255,182,217,0.15)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
