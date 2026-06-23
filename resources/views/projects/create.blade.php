@extends('layouts.tracker')

@section('content')
    <div class="mb-3">
        <a href="{{ route('projects.index') }}" class="text-muted">← Back to Projects</a>
    </div>

    <h1>New Project</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date') }}">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                value="{{ old('deadline') }}">
            @error('deadline')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
@endsection
