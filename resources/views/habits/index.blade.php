@extends('layouts.app')

@section('page-title', 'My Habits')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
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
    <div class="row g-3">
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
            $progress = $habit->target_days > 0
                ? min(100, round(($logsThisWeek / $habit->target_days) * 100))
                : 0;
        @endphp
        <div class="col-md-4 col-sm-6">
            <div class="card h-100" style="border-top: 3px solid {{ $habit->color }};">
                <div class="card-body" style="padding:20px;">

                    <!-- Icon + Name -->
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:44px;height:44px;border-radius:10px;
                                    background:{{ $habit->color }}1A;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ti ti-{{ $habit->icon }}"
                               style="font-size:22px;color:{{ $habit->color }};"></i>
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:700;color:#333;font-size:15px;
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $habit->name }}
                            </div>
                            <div style="font-size:12px;color:#bbb;margin-top:1px;">
                                {{ ucfirst($habit->category ?? '—') }} · {{ ucfirst($habit->frequency) }}
                            </div>
                        </div>
                    </div>

                    <!-- Weekly progress bar -->
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;justify-content:space-between;
                                    font-size:12px;color:#aaa;margin-bottom:5px;">
                            <span>This week</span>
                            <span>{{ $logsThisWeek }}/{{ $habit->target_days }} days</span>
                        </div>
                        <div style="height:5px;background:#FFE5F0;border-radius:99px;overflow:hidden;">
                            <div style="height:100%;width:{{ $progress }}%;
                                        background:{{ $habit->color }};border-radius:99px;
                                        transition:width .3s;"></div>
                        </div>
                    </div>

                    <!-- Stats row -->
                    <div style="display:flex;gap:16px;margin-bottom:16px;">
                        <div>
                            <div style="font-size:16px;font-weight:700;color:#333;">{{ $streak }}</div>
                            <div style="font-size:11px;color:#bbb;text-transform:uppercase;">Streak</div>
                        </div>
                        <div>
                            <span style="padding:3px 10px;background:#FFE5F0;color:#FF8FB3;
                                         border-radius:20px;font-size:12px;font-weight:600;">
                                {{ ucfirst($habit->status) }}
                            </span>
                        </div>
                        @if ($loggedToday)
                            <div style="margin-left:auto;">
                                <span style="font-size:12px;color:#6BCB77;font-weight:600;">
                                    <i class="ti ti-circle-check"></i> Logged
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div style="display:flex;gap:8px;align-items:center;">
                        @if (!$loggedToday)
                            <form action="/habits/{{ $habit->id }}/log" method="POST" style="flex:1;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                                    <i class="ti ti-check me-1"></i>Log Today
                                </button>
                            </form>
                        @else
                            <div style="flex:1;"></div>
                        @endif
                        <a href="/habits/{{ $habit->id }}/edit"
                           class="btn btn-secondary btn-sm"
                           title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <form action="/habits/{{ $habit->id }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this habit and all its logs?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" title="Delete"
                                    style="padding:5px 10px;background:transparent;
                                           border:1px solid #FFD6E8;color:#FF6B6B;border-radius:8px;">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
