@extends('layouts.tracker')

@section('content')
    <div class="mb-3">
        <a href="{{ route('projects.index') }}" class="text-muted">← Back to Projects</a>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <h1>{{ $project->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">Edit</a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                onsubmit="return confirm('Delete this project?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <p>{{ $project->description }}</p>

    @if ($project->start_date || $project->deadline)
        <p class="text-muted small">
            @if ($project->start_date)
                Start: {{ $project->start_date }}
            @endif
            @if ($project->deadline)
                &nbsp;|&nbsp; Deadline: {{ $project->deadline }}
            @endif
        </p>
    @endif

    <hr>

    <div class="d-flex justify-content-between mb-3">
        <h4>Issues</h4>
        <a href="{{ route('projects.issues.create', $project) }}" class="btn btn-primary btn-sm">+ New Issue</a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach (['open', 'in_progress', 'closed'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                @foreach (['low', 'medium', 'high'] as $p)
                    <option value="{{ $p }}" @selected(request('priority') === $p)>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="tag" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(request('tag') == $tag->id)>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Issues Table --}}
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Tags</th>
                <th>Due Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($issues as $issue)
                <tr>
                    <td><a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a></td>
                    <td><span class="badge bg-secondary">{{ $issue->status }}</span></td>
                    <td>{{ $issue->priority }}</td>
                    <td>
                        @foreach ($issue->tags as $tag)
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </td>
                    <td>{{ $issue->due_date ?? '-' }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                            onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No issues found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $issues->links() }}
@endsection
