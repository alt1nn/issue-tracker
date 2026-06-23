<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

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
        $issue->load(['project', 'tags']);
        $allTags = Tag::all();
        return view('issues.show', compact('issue', 'allTags'));
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
}