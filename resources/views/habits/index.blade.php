@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: #4A4A4A;">My Habits</h2>
    <a href="/habits/create" class="btn btn-primary">
        <i class="ti ti-plus"></i> Add Habit
    </a>
</div>

@if ($habits->isEmpty())
    <div class="alert" style="background-color: #FFE5F0; border: 1px solid #FFD6E8; color: #4A4A4A;">
        No habits yet.
        <a href="/habits/create" style="color: #FFB6D9;">Create your first habit!</a>
    </div>
@else
    <div class="row g-3">
        @foreach ($habits as $habit)
        @php
            $logsThisWeek = $habit->logs()
                ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('completed', true)
                ->count();
            $loggedToday = $habit->logs()
                ->whereDate('logged_date', today())
                ->where('completed', true)
                ->exists();
        @endphp
        <div class="col-md-4">
            <div class="card h-100" style="border-left: 5px solid {{ $habit->color }};">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="ti ti-{{ $habit->icon }}" style="color: {{ $habit->color }};"></i>
                        {{ $habit->name }}
                    </h5>
                    <p class="text-muted small mb-2">{{ ucfirst($habit->category ?? '') }}</p>

                    <!-- Weekly progress bar -->
                    <div class="mb-3">
                        <small>This week: {{ $logsThisWeek }}/{{ $habit->target_days }}</small>
                        <div class="progress" style="height: 6px; background-color: #FFE5F0;">
                            <div class="progress-bar"
                                 style="width: {{ min(100, ($logsThisWeek / max(1, $habit->target_days)) * 100) }}%;
                                        background-color: {{ $habit->color }};"></div>
                        </div>
                    </div>

                    <!-- Log / Logged buttons -->
                    @if (! $loggedToday)
                        <form action="/habits/{{ $habit->id }}/log" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary me-1">
                                <i class="ti ti-check"></i> Log Today
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm me-1" disabled
                                style="background-color: #6BCB77; color: #fff; border: none;">
                            <i class="ti ti-circle-check"></i> Logged
                        </button>
                    @endif

                    <a href="/habits/{{ $habit->id }}" class="btn btn-sm btn-outline-secondary me-1">
                        <i class="ti ti-eye"></i>
                    </a>
                    <a href="/habits/{{ $habit->id }}/edit" class="btn btn-sm btn-outline-secondary me-1">
                        <i class="ti ti-pencil"></i>
                    </a>
                    <form action="/habits/{{ $habit->id }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this habit?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="ti ti-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
