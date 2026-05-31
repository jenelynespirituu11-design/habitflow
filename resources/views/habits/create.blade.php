@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title mb-4" style="color: #FFB6D9;">
                    <i class="ti ti-plus"></i> Create New Habit
                </h2>

                <form action="/habits" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Habit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Morning Run" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="What is this habit about?">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select category</option>
                                @foreach (['health', 'productivity', 'learning', 'fitness', 'mindfulness', 'custom'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <select name="frequency" class="form-select" required>
                                <option value="daily" {{ old('frequency') === 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target Days (1–7) <span class="text-danger">*</span></label>
                            <input type="number" name="target_days" class="form-control"
                                   min="1" max="7" value="{{ old('target_days', 7) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Color <span class="text-danger">*</span></label>
                            <input type="color" name="color" class="form-control form-control-color"
                                   value="{{ old('color', '#FFB6D9') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Icon <span class="text-danger">*</span></label>
                            <select name="icon"
                                    class="form-select @error('icon') is-invalid @enderror" required>
                                <option value="">Select icon</option>
                                @foreach (['star','heart','flame','target','book','dumbbell','moon','sun','droplet','headphones','pencil','coffee'] as $icon)
                                    <option value="{{ $icon }}" {{ old('icon') === $icon ? 'selected' : '' }}>
                                        {{ ucfirst($icon) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">Create Habit</button>
                        <a href="/habits" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
