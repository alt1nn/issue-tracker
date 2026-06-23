@extends('layouts.tracker')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Tags</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><strong>New Tag</strong></div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('tags.store') }}" method="POST">
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
                            <label class="form-label">Color</label>
                            <input type="color" name="color"
                                class="form-control form-control-color @error('color') is-invalid @enderror"
                                value="{{ old('color', '#6c757d') }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Create Tag</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Color</th>
                        <th>Name</th>
                        <th>Issues</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>
                                <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                    &nbsp;&nbsp;&nbsp;
                                </span>
                            </td>
                            <td>{{ $tag->name }}</td>
                            <td>{{ $tag->issues_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted text-center">No tags yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
