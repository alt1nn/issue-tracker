<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;

class IssueController extends Controller
{
    public function create(Project $project)
    {
        return view('issues.create', compact('project'));
    }

    public function store(StoreIssueRequest $request, Project $project)
    {
        $project->issues()->create($request->validated());
        return redirect()->route('projects.show', $project)->with('success', 'Issue created!');
    }

    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'members']);
        $allTags = Tag::all();
        $allUsers = User::all();
        return view('issues.show', compact('issue', 'allTags', 'allUsers'));
    }
    public function edit(Issue $issue)
    {
        return view('issues.edit', compact('issue'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());
        return redirect()->route('issues.show', $issue)->with('success', 'Issue updated!');
    }

    public function destroy(Issue $issue)
    {
        $project = $issue->project;
        $issue->delete();
        return redirect()->route('projects.show', $project)->with('success', 'Issue deleted!');
    }

    public function attachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->syncWithoutDetaching([$tag->id]);
        return response()->json(['message' => 'Tag attached', 'tag' => $tag]);
    }

    public function detachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->detach($tag->id);
        return response()->json(['message' => 'Tag detached']);
    }

    public function attachMember(Issue $issue, User $user)
    {
        $issue->members()->syncWithoutDetaching([$user->id]);
        return response()->json(['message' => 'Member attached', 'user' => $user]);
    }

    public function detachMember(Issue $issue, User $user)
    {
        $issue->members()->detach($user->id);
        return response()->json(['message' => 'Member detached']);
    }

    public function search(Project $project)
    {
        $query = request('q');

        $issues = $project->issues()
            ->with('tags')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        return response()->json($issues);
    }
}