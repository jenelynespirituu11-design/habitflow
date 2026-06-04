@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:11px;color:#bbb;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Today's Completed</div>
                <div style="font-size:36px;font-weight:700;color:#FFB6D9;">{{ $todayCompleted }}</div>
                <div style="font-size:12px;color:#ccc;margin-top:4px;">out of active habits</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:11px;color:#bbb;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Active Habits</div>
                <div style="font-size:36px;font-weight:700;color:#FFB6D9;">{{ $activeHabits }}</div>
                <div style="font-size:12px;color:#ccc;margin-top:4px;">tracking now</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:11px;color:#bbb;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">This Week</div>
                <div style="font-size:36px;font-weight:700;color:#FFB6D9;">{{ $weekCompleted }}</div>
                <div style="font-size:12px;color:#ccc;margin-top:4px;">completions</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:11px;color:#bbb;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Current Streak</div>
                <div style="font-size:36px;font-weight:700;color:#FFB6D9;">{{ $currentStreak }}</div>
                <div style="font-size:12px;color:#ccc;margin-top:4px;">days</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="card mb-4">
    <div class="card-body">
        <h6 style="font-weight:700;color:#333;margin-bottom:16px;">Weekly Completions</h6>
        <div style="position:relative;height:220px;">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Habits -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px 16px;">
            <h6 style="font-weight:700;color:#333;margin:0;">Active Habits</h6>
            <a href="/habits" style="font-size:13px;color:#FFB6D9;text-decoration:none;font-weight:600;">View All →</a>
        </div>

        @if ($recentHabits->isEmpty())
            <div style="text-align:center;padding:40px;color:#bbb;font-size:14px;">
                No active habits yet.
                <a href="/habits/create" style="color:#FFB6D9;font-weight:600;">Create your first habit →</a>
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Habit</th>
                        <th>Category</th>
                        <th>Today</th>
                        <th>This Week</th>
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
                        $weekLogs = \App\Models\HabitLog::where('habit_id', $habit->id)
                            ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
                            ->where('completed', true)
                            ->count();
                    @endphp
                    <tr>
                        <td>
                            <span style="font-weight:600;color:#333;">{{ $habit->name }}</span>
                        </td>
                        <td>{{ ucfirst($habit->category ?? '—') }}</td>
                        <td>
                            @if ($logged)
                                <span style="color:#6BCB77;font-weight:600;">✓ Done</span>
                            @else
                                <span style="color:#ddd;">✗ Pending</span>
                            @endif
                        </td>
                        <td style="color:#888;">{{ $weekLogs }}/{{ $habit->target_days }}</td>
                        <td>
                            @if (!$logged)
                                <form action="/habits/{{ $habit->id }}/log" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Log</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:#bbb;">Logged</span>
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
            label: 'Completed',
            data: {!! json_encode($chartData['completed']) !!},
            borderColor: '#FFB6D9',
            backgroundColor: 'rgba(255,182,217,0.10)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#FFB6D9',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, color: '#bbb' }, grid: { color: '#FFF0F7' } },
            x: { ticks: { color: '#bbb' }, grid: { display: false } }
        }
    }
});
</script>
@endpush
