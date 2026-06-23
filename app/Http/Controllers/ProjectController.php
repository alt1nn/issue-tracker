<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('issues')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project created!');
    }

    public function show(Project $project)
    {
        $issues = $project->issues()
            ->with('tags')
            ->when(request('status'), fn($q, $v) => $q->where('status', $v))
            ->when(request('priority'), fn($q, $v) => $q->where('priority', $v))
            ->when(request('tag'), fn($q, $v) => $q->whereHas('tags', fn($tq) => $tq->where('tags.id', $v)))
            ->latest()
            ->paginate(10);

        $tags = Tag::all();

        return view('projects.show', compact('project', 'issues', 'tags'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('projects.show', $project)->with('success', 'Project updated!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted!');
    }
}