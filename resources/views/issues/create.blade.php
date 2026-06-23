@extends('layouts.tracker')

@section('content')
    <div class="mb-3">
        <a href="{{ route('projects.show', $project) }}" class="text-muted">← Back to {{ $project->name }}</a>
    </div>

    <h1>New Issue</h1>

    <form action="{{ route('projects.issues.store', $project) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status *</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
                @foreach (['open', 'in_progress', 'closed'] as $s)
                    <option value="{{ $s }}" @selected(old('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Priority *</label>
            <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                @foreach (['low', 'medium', 'high'] as $p)
                    <option value="{{ $p }}" @selected(old('priority') === $p)>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
            @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                value="{{ old('due_date') }}">
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Issue</button>
    </form>
@endsection
