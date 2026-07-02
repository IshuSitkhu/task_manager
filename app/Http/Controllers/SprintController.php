<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->latest()->get();

        foreach ($sprints as $sprint){
            $sprint->backlogTasks = $sprint->tasks->filter(function ($task){
                return $task->due_date &&
                    Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                    $task->status != 'done';
            });
        }

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


    public function edit(Project $project, Sprint $sprint)
    {
        return view('sprints.edit', compact('project', 'sprint'));
    }

    // UPDATE
    public function update(Request $request, Project $project, Sprint $sprint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:planned,active,closed',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $sprint->update($request->all());

        return redirect()->route('projects.sprints', $project->id)
            ->with('success', 'Sprint updated successfully');
    }

    // DELETE
    public function destroy(Project $project, Sprint $sprint)
    {
        $sprint->delete();

        return back()->with('success', 'Sprint deleted successfully');
    }


}
