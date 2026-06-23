@extends('layouts.tracker')

@section('content')
    <div class="mb-3">
        <a href="{{ route('projects.show', $issue->project) }}" class="text-muted">← {{ $issue->project->name }}</a>
    </div>

    <div class="d-flex justify-content-between mb-2">
        <h1>{{ $issue->title }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-secondary">Edit</a>
            <form action="{{ route('issues.destroy', $issue) }}" method="POST" onsubmit="return confirm('Delete issue?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <p>
        <span class="badge bg-secondary">{{ $issue->status }}</span>
        <span class="badge bg-info text-dark">{{ $issue->priority }}</span>
        @if ($issue->due_date)
            <small class="text-muted ms-2">Due: {{ $issue->due_date }}</small>
        @endif
    </p>

    <p>{{ $issue->description }}</p>

    <hr>

    {{-- TAGS SECTION --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Tags</strong>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagModal">
                Manage Tags
            </button>
        </div>
        <div class="card-body" id="attached-tags">
            @foreach ($issue->tags as $tag)
                <span class="badge me-1" style="background-color: {{ $tag->color ?? '#6c757d' }}"
                    id="tag-badge-{{ $tag->id }}">{{ $tag->name }}</span>
            @endforeach
        </div>
    </div>

    {{-- TAG MODAL --}}
    <div class="modal fade" id="tagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attach / Detach Tags</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @foreach ($allTags as $tag)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                {{ $tag->name }}
                            </span>
                            @if ($issue->tags->contains($tag))
                                <button class="btn btn-sm btn-danger tag-btn" data-tag-id="{{ $tag->id }}"
                                    data-action="detach">Detach</button>
                            @else
                                <button class="btn btn-sm btn-success tag-btn" data-tag-id="{{ $tag->id }}"
                                    data-action="attach">Attach</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- COMMENTS SECTION --}}
    <h4>Comments</h4>

    <div id="comments-list" class="mb-4"></div>
    <button id="load-more" class="btn btn-outline-secondary btn-sm mb-4" style="display:none;">Load More</button>

    {{-- ADD COMMENT FORM --}}
    <div class="card">
        <div class="card-header"><strong>Add Comment</strong></div>
        <div class="card-body">
            <div id="comment-errors" class="alert alert-danger" style="display:none;"></div>
            <div class="mb-3">
                <label class="form-label">Your Name *</label>
                <input type="text" id="author_name" class="form-control" placeholder="Your name">
            </div>
            <div class="mb-3">
                <label class="form-label">Comment *</label>
                <textarea id="body" class="form-control" rows="3" placeholder="Write a comment..."></textarea>
            </div>
            <button id="submit-comment" class="btn btn-primary">Post Comment</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const issueId = {{ $issue->id }};
        let nextPageUrl = `/issues/${issueId}/comments?page=1`;

        // Load comments
        function loadComments(url) {
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    data.data.forEach(comment => {
                        document.getElementById('comments-list').insertAdjacentHTML('beforeend', commentHtml(
                            comment));
                    });
                    if (data.next_page_url) {
                        nextPageUrl = data.next_page_url;
                        document.getElementById('load-more').style.display = 'block';
                    } else {
                        document.getElementById('load-more').style.display = 'none';
                    }
                });
        }

        function commentHtml(comment) {
            return `
            <div class="card mb-2">
                <div class="card-body">
                    <strong>${comment.author_name}</strong>
                    <small class="text-muted ms-2">${comment.created_at}</small>
                    <p class="mb-0 mt-1">${comment.body}</p>
                </div>
            </div>`;
        }

        loadComments(nextPageUrl);

        document.getElementById('load-more').addEventListener('click', () => loadComments(nextPageUrl));

        // Submit comment
        document.getElementById('submit-comment').addEventListener('click', () => {
            const author_name = document.getElementById('author_name').value;
            const body = document.getElementById('body').value;

            fetch(`/issues/${issueId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        author_name,
                        body
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.errors) {
                        const errDiv = document.getElementById('comment-errors');
                        errDiv.style.display = 'block';
                        errDiv.innerHTML = Object.values(data.errors).flat().join('<br>');
                        return;
                    }
                    document.getElementById('comment-errors').style.display = 'none';
                    document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHtml(
                    data));
                    document.getElementById('author_name').value = '';
                    document.getElementById('body').value = '';
                });
        });

        // Tag attach/detach
        document.querySelectorAll('.tag-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tagId = btn.dataset.tagId;
                const action = btn.dataset.action;
                const method = action === 'attach' ? 'POST' : 'DELETE';

                fetch(`/issues/${issueId}/tags/${tagId}/${action}`, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(() => location.reload());
            });
        });
    </script>
@endpush
