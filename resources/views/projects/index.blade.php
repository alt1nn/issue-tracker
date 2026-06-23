@extends('layouts.tracker')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Projects</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="text-muted small">{{ $project->issues_count }} issues</p>
                        <p>{{ Str::limit($project->description, 80) }}</p>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST"
                            onsubmit="return confirm('Delete this project?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No projects yet.</p>
        @endforelse
    </div>

    {{ $projects->links() }}
@endsection
