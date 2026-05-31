@extends('layouts.app')

@section('title', 'Progress')
@section('page-title', 'Progress')

@push('head')
<style>
/* ── Page layout ─────────────────────────────────────────────────────────── */
.progress-page { display: flex; flex-direction: column; gap: 24px; }

/* ── Section heading ─────────────────────────────────────────────────────── */
.section-heading {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.7px; color: var(--text-muted);
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.section-heading i { font-size: 16px; color: var(--pink-400); }

/* ── Stat cards row ──────────────────────────────────────────────────────── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.summary-card {
    background: var(--white);
    border: 1px solid var(--pink-300);
    border-radius: var(--radius);
    box-shadow: 0 2px 8px rgba(255,182,217,0.1);
    padding: 22px 20px;
    display: flex; flex-direction: column; gap: 6px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.summary-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,182,217,0.2); }
.summary-card-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 4px;
}
.summary-card-icon i { font-size: 21px; }
.ic-pink   { background: var(--pink-100); }
.ic-pink i { color: var(--pink-600); }
.ic-green   { background: #E8F9F0; }
.ic-green i { color: #3A9E6F; }
.ic-purple   { background: #F3EEFF; }
.ic-purple i { color: #8B5CF6; }
.ic-amber   { background: #FFF5E0; }
.ic-amber i { color: #E5901C; }

.summary-val {
    font-size: 28px; font-weight: 800; color: var(--text-dark); line-height: 1;
}
.summary-val span { font-size: 14px; font-weight: 500; color: var(--text-light); }
.summary-lbl { font-size: 12px; color: var(--text-muted); font-weight: 500; }

/* ── Charts row ──────────────────────────────────────────────────────────── */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.chart-card {
    background: var(--white);
    border: 1px solid var(--pink-300);
    border-radius: var(--radius);
    box-shadow: 0 2px 8px rgba(255,182,217,0.1);
    padding: 22px 24px;
}
.chart-card-title {
    font-size: 14px; font-weight: 700; color: var(--text-dark);
    margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
}
.chart-card-title i { color: var(--pink-500); font-size: 18px; }

/* ── 30-day heatmap ──────────────────────────────────────────────────────── */
.heatmap-card {
    background: var(--white);
    border: 1px solid var(--pink-300);
    border-radius: var(--radius);
    box-shadow: 0 2px 8px rgba(255,182,217,0.1);
    padding: 22px 24px;
}
.heatmap-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 7px;
}
.heatmap-day {
    aspect-ratio: 1;
    border-radius: 6px;
    background: var(--pink-100);
    position: relative;
    cursor: default;
    transition: transform 0.15s;
}
.heatmap-day:hover { transform: scale(1.15); z-index: 1; }
.heatmap-day.today { outline: 2px solid var(--pink-500); outline-offset: 1px; }
.heatmap-day[data-count="0"]  { background: var(--pink-100); }
.heatmap-day[data-count="1"]  { background: #FFC8E8; }
.heatmap-day[data-count="2"]  { background: #FFB6D9; }
.heatmap-day[data-count="3"]  { background: #FF99C8; }
.heatmap-day[data-count="4"]  { background: #FF85C0; }

/* tooltip on hover */
.heatmap-day::after {
    content: attr(data-tip);
    position: absolute;
    bottom: calc(100% + 6px);
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    background: rgba(45,31,45,0.85);
    color: #fff;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s;
    z-index: 10;
}
.heatmap-day:hover::after { opacity: 1; }

.heatmap-legend {
    display: flex; align-items: center; gap: 6px;
    margin-top: 12px; font-size: 11px; color: var(--text-muted);
    justify-content: flex-end;
}
.legend-swatch {
    width: 14px; height: 14px; border-radius: 3px;
}

/* ── Per-habit table ─────────────────────────────────────────────────────── */
.habit-table-card {
    background: var(--white);
    border: 1px solid var(--pink-300);
    border-radius: var(--radius);
    box-shadow: 0 2px 8px rgba(255,182,217,0.1);
    overflow: hidden;
}
.habit-table-card table { width: 100%; border-collapse: collapse; }
.habit-table-card thead tr { background: var(--pink-100); }
.habit-table-card th {
    padding: 11px 16px; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.6px;
    color: var(--text-muted); text-align: left;
    border-bottom: 1px solid var(--pink-200);
}
.habit-table-card td {
    padding: 13px 16px; font-size: 13px;
    color: var(--text-dark); border-bottom: 1px solid var(--pink-100);
    vertical-align: middle;
}
.habit-table-card tbody tr:last-child td { border-bottom: none; }
.habit-table-card tbody tr:hover td { background: var(--pink-50); }

.habit-name-cell {
    display: flex; align-items: center; gap: 10px;
}
.habit-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.habit-icon-sm {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.habit-icon-sm i { font-size: 16px; }

.rate-bar-wrap { display: flex; align-items: center; gap: 10px; min-width: 120px; }
.rate-bar-track {
    flex: 1; height: 6px; border-radius: 99px; background: var(--pink-100); overflow: hidden;
}
.rate-bar-fill {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg, var(--pink-400), var(--pink-500));
    transition: width 0.6s ease;
}
.rate-text { font-size: 12px; font-weight: 700; color: var(--text-dark); white-space: nowrap; }

.tag-badge {
    display: inline-flex; align-items: center;
    padding: 2px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
}
.tag-active    { background: #E8F9F0; color: #2E8A5E; }
.tag-paused    { background: #FFF8E6; color: #B8861C; }
.tag-completed { background: #EEF0FF; color: #5B67E8; }

/* ── Empty state ─────────────────────────────────────────────────────────── */
.empty-progress {
    text-align: center; padding: 60px 24px;
    color: var(--text-light);
}
.empty-progress i { font-size: 48px; color: var(--pink-300); margin-bottom: 14px; display: block; }
.empty-progress h3 { font-size: 17px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width: 1024px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .charts-row   { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .summary-grid   { grid-template-columns: repeat(2, 1fr); }
    .heatmap-grid   { grid-template-columns: repeat(6, 1fr); }
    .habit-table-card td:nth-child(4),
    .habit-table-card th:nth-child(4) { display: none; }
}
</style>
@endpush

@section('content')
<div class="progress-page">

    {{-- ── Summary stats ──────────────────────────────────────────────────── --}}
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-icon ic-pink"><i class="ti ti-flame"></i></div>
            <div class="summary-val">{{ $currentStreak }}<span> day{{ $currentStreak !== 1 ? 's' : '' }}</span></div>
            <div class="summary-lbl">Current Streak</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon ic-green"><i class="ti ti-circle-check"></i></div>
            <div class="summary-val">{{ $totalLogs }}</div>
            <div class="summary-lbl">Total Completions</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon ic-purple"><i class="ti ti-trophy"></i></div>
            <div class="summary-val">{{ $bestStreak }}<span> day{{ $bestStreak !== 1 ? 's' : '' }}</span></div>
            <div class="summary-lbl">Best Streak Ever</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon ic-amber"><i class="ti ti-chart-bar"></i></div>
            <div class="summary-val">{{ $overallRate }}<span>%</span></div>
            <div class="summary-lbl">30-Day Rate</div>
        </div>
    </div>

    @if($activeCount === 0 && $totalLogs === 0)
    {{-- Empty state --}}
    <div class="heatmap-card">
        <div class="empty-progress">
            <i class="ti ti-chart-line"></i>
            <h3>No data yet</h3>
            <p>Start logging your habits to see progress here.</p>
            <a href="{{ route('habits.index') }}" style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;padding:9px 20px;background:linear-gradient(135deg,var(--pink-400),var(--pink-500));color:#fff;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;">
                <i class="ti ti-arrow-right"></i> Go to My Habits
            </a>
        </div>
    </div>
    @else

    {{-- ── Charts row ──────────────────────────────────────────────────────── --}}
    <div class="charts-row">

        {{-- Monthly trend bar chart --}}
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="ti ti-chart-bar"></i>
                Monthly Completion (last 6 months)
            </div>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>

        {{-- Best day + quick facts --}}
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="ti ti-info-circle"></i>
                At a Glance
            </div>
            <div style="display:flex;flex-direction:column;gap:16px;">

                <div style="padding:16px 18px;background:var(--pink-50);border-radius:10px;border:1px solid var(--pink-200);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--text-muted);margin-bottom:6px;">Best Day of the Week</div>
                    <div style="font-size:22px;font-weight:800;color:var(--text-dark);display:flex;align-items:center;gap:8px;">
                        <i class="ti ti-calendar-star" style="color:var(--pink-500);font-size:22px;"></i>
                        {{ $bestDayStr }}
                    </div>
                </div>

                <div style="padding:16px 18px;background:var(--pink-50);border-radius:10px;border:1px solid var(--pink-200);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--text-muted);margin-bottom:6px;">Active Habits Tracked</div>
                    <div style="font-size:22px;font-weight:800;color:var(--text-dark);display:flex;align-items:center;gap:8px;">
                        <i class="ti ti-checklist" style="color:var(--pink-500);font-size:22px;"></i>
                        {{ $activeCount }} habit{{ $activeCount !== 1 ? 's' : '' }}
                    </div>
                </div>

                <div style="padding:16px 18px;background:var(--pink-50);border-radius:10px;border:1px solid var(--pink-200);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--text-muted);margin-bottom:6px;">30-Day Completion Rate</div>
                    <div style="margin-top:6px;">
                        <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-light);margin-bottom:6px;">
                            <span>Progress</span><span style="font-weight:700;color:var(--text-dark);">{{ $overallRate }}%</span>
                        </div>
                        <div style="height:8px;background:var(--pink-100);border-radius:4px;overflow:hidden;">
                            <div class="progress-fill"
                                 style="height:100%;border-radius:4px;background:linear-gradient(90deg,var(--pink-400),var(--pink-500));width:{{ min(100,$overallRate) }}%;transition:width 0.6s ease;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── 30-day heatmap ───────────────────────────────────────────────────── --}}
    <div class="heatmap-card">
        <div class="chart-card-title" style="margin-bottom:14px;">
            <i class="ti ti-calendar-month"></i>
            Last 30 Days — Activity
        </div>

        <div class="heatmap-grid">
            @foreach($heatmap as $day)
            @php
                $cap = min($day['count'], 4);
                $tip = $day['label'] . ' — ' . ($day['count'] === 0 ? 'No logs' : $day['count'] . ' habit' . ($day['count'] !== 1 ? 's' : '') . ' completed');
            @endphp
            <div class="heatmap-day {{ $day['isToday'] ? 'today' : '' }}"
                 data-count="{{ $cap }}"
                 data-tip="{{ $tip }}">
            </div>
            @endforeach
        </div>

        <div class="heatmap-legend">
            <span>Less</span>
            <div class="legend-swatch" style="background:var(--pink-100);"></div>
            <div class="legend-swatch" style="background:#FFC8E8;"></div>
            <div class="legend-swatch" style="background:#FFB6D9;"></div>
            <div class="legend-swatch" style="background:#FF99C8;"></div>
            <div class="legend-swatch" style="background:#FF85C0;"></div>
            <span>More</span>
        </div>
    </div>

    {{-- ── Per-habit breakdown ──────────────────────────────────────────────── --}}
    <div>
        <div class="section-heading"><i class="ti ti-list-details"></i> Habit Breakdown</div>
        <div class="habit-table-card">
            <table>
                <thead>
                    <tr>
                        <th>Habit</th>
                        <th>Status</th>
                        <th>Completions</th>
                        <th>Streak</th>
                        <th>Longest</th>
                        <th>Completion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($habitStats as $row)
                    @php
                        /** @var \App\Models\Habit $h */
                        $h = $row['habit'];
                    @endphp
                    <tr>
                        <td>
                            <div class="habit-name-cell">
                                <div class="habit-icon-sm"
                                     data-icon-bg="{{ $h->color }}"
                                     style="background:{{ $h->color }}22;">
                                    <i class="ti ti-{{ $h->icon }}"
                                       data-icon-color="{{ $h->color }}"
                                       style="color:{{ $h->color }};"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">{{ $h->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $h->category }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="tag-badge tag-{{ $h->status }}">{{ ucfirst($h->status) }}</span>
                        </td>
                        <td style="font-weight:700;">
                            {{ $row['done'] }}
                            <span style="font-size:11px;font-weight:400;color:var(--text-muted);">/ {{ $row['days'] }} days</span>
                        </td>
                        <td>
                            <span style="font-weight:700;">{{ $row['streak'] }}</span>
                            <span style="font-size:11px;color:var(--text-muted);"> day{{ $row['streak'] !== 1 ? 's' : '' }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700;">{{ $row['longest'] }}</span>
                            <span style="font-size:11px;color:var(--text-muted);"> day{{ $row['longest'] !== 1 ? 's' : '' }}</span>
                        </td>
                        <td>
                            <div class="rate-bar-wrap">
                                <div class="rate-bar-track">
                                    <div class="rate-bar-fill" style="width:{{ min(100, $row['rate']) }}%;"></div>
                                </div>
                                <div class="rate-text">{{ $row['rate'] }}%</div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">
                            No habits yet. <a href="{{ route('habits.index') }}" style="color:var(--pink-600);">Add one!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const monthlyCtx = document.getElementById('monthlyChart');
    if (!monthlyCtx) return;

    const labels = @json($monthlyLabels);
    const rates  = @json($monthlyRates);

    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Completion %',
                data: rates,
                backgroundColor: 'rgba(255,182,217,0.7)',
                borderColor: '#FF8FB3',
                borderWidth: 1.5,
                borderRadius: 7,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y}% completion`,
                    },
                },
            },
            scales: {
                y: {
                    min: 0, max: 100,
                    ticks: {
                        callback: v => v + '%',
                        stepSize: 25,
                        color: '#B8A8B8',
                        font: { size: 11 },
                    },
                    grid: { color: 'rgba(255,182,217,0.15)' },
                    border: { display: false },
                },
                x: {
                    ticks: { color: '#9B8A9B', font: { size: 11 } },
                    grid: { display: false },
                    border: { display: false },
                },
            },
        },
    });
});
</script>
@endpush
