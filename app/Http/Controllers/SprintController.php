<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\ActivityService;

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
            $sprint->normalTasks = $sprint->tasks->reject(function ($task) {
                        return $task->due_date &&
                            Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                            $task->status != 'done';
                    });
        }

        $backlogSprints = $sprints->filter(function ($sprint){
            return $sprint->backlogTasks->count() > 0;
        });

        return view('sprints.index', compact('project', 'sprints', 'backlogSprints'));
    }

    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project, ActivityService $activityService)
    {
        $request->validate([
            'name' => 'required|max:255',
            'goal' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $sprint =$project->sprints()->create([
            'name' => $request->name,
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'progress' => $request->progress ?? 0,
        ]);

        $activityService->log(
            Auth::user(),
            'created_sprint',
            'Created sprint "' . $sprint->name . '"',
            $sprint
        );

        return redirect()
            ->route('projects.sprints', $project->id)
            ->with('success', 'Sprint created successfully');
    }


    public function edit(Project $project, Sprint $sprint)
    {
        return view('sprints.edit', compact('project', 'sprint'));
    }

    // UPDATE
    public function update(Request $request, Project $project, Sprint $sprint, ActivityService $activityService)
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

        $activityService->log(
            Auth::user(),
            'updated_sprint',
            'Updated sprint "' . $sprint->name . '"',
            $sprint
        );

        return redirect()->route('projects.sprints', $project->id)
            ->with('success', 'Sprint updated successfully');
    }

    // DELETE
    public function destroy(Project $project, Sprint $sprint, ActivityService $activityService)
    {
        $activityService->log(
            Auth::user(),
            'deleted_sprint',
            'Deleted sprint "' . $sprint->name . '"',
            $sprint
        );

        $sprint->delete();

        return back()->with('success', 'Sprint deleted successfully');
    }

}
