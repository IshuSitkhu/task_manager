<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->latest()->get();

        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|max:255',
            'goal' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project->sprints()->create([
            'name' => $request->name,
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'progress' => $request->progress ?? 0,
        ]);

        return redirect()
            ->route('projects.sprints', $project->id)
            ->with('success', 'Sprint created successfully');
    }
}
