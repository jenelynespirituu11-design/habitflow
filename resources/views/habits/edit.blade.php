@extends('layouts.app')

@section('page-title', 'Edit Habit')

@section('content')

<nav style="font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:6px;color:#bbb;">
    <a href="/habits" style="color:#FFB6D9;text-decoration:none;font-weight:500;">My Habits</a>
    <i class="ti ti-chevron-right" style="font-size:12px;"></i>
    <span style="color:#888;">Edit Habit</span>
</nav>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h6 style="font-weight:700;color:#333;margin-bottom:20px;">Edit Habit</h6>

                <form action="/habits/{{ $habit->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Habit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $habit->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $habit->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                @foreach (['health', 'productivity', 'learning', 'fitness', 'mindfulness', 'custom'] as $cat)
                                    <option value="{{ $cat }}"
                                        {{ old('category', $habit->category) === $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <select name="frequency" class="form-select" required>
                                <option value="daily"  {{ old('frequency', $habit->frequency) === 'daily'  ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency', $habit->frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target Days (1–7)</label>
                            <input type="number" name="target_days" class="form-control"
                                   min="1" max="7"
                                   value="{{ old('target_days', $habit->target_days) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" class="form-control form-control-color"
                                   value="{{ old('color', $habit->color) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Icon</label>
                            <select name="icon" class="form-select" required>
                                @foreach (['star','heart','flame','target','book','dumbbell','moon','sun','droplet','headphones','pencil','coffee'] as $icon)
                                    <option value="{{ $icon }}"
                                        {{ old('icon', $habit->icon) === $icon ? 'selected' : '' }}>
                                        {{ ucfirst($icon) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">Update Habit</button>
                        <a href="/habits" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
