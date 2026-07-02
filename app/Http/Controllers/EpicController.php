<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EpicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $epics = $project->epics()
            ->withCount('tasks')
            ->with([
                'owner',
                'tasks.sprint',
                'tasks.projectStatus',
                'tasks.type',
            ])
            ->latest()
            ->get();

            foreach ($epics as $epic) {

                    $epic->backlogTasks = $epic->tasks->filter(function ($task) {
                        return $task->due_date &&
                            Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                            $task->status != 'done';
                    });

                    $epic->normalTasks = $epic->tasks->reject(function ($task) {
                        return $task->due_date &&
                            Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                            $task->status != 'done';
                    });
            }

            $backlogEpics = $epics->filter(function ($epic) {
                    return $epic->backlogTasks->count() > 0;
            });

        return view('epics.index', compact(
            'project',
            'epics',
            'backlogEpics'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $users = $project->members; // ON;LY AVAILABLE PROJECT_USER

        return view('epics.create', compact('project', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'priority' => 'required',
            'status' => 'required',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'required|date',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project->epics()->create([
            'title' => $request->title,
            'description' => $request->description,
            'owner_id' => $request->owner_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'progress' => $request->progress ?? 0,
        ]);

        return redirect()->route('projects.epics', $project->id)
            ->with('success', 'Epic created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Epic $epic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Epic $epic)
    {
        $users = User::where('role', 'employee')->get();

        return view('epics.edit', compact('project', 'epic', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Project $project, Epic $epic)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'owner_id' => 'required|exists:users,id',
        'priority' => 'required',
        'status' => 'required',
        'planned_start_date' => 'nullable|date',
        'planned_end_date' => 'nullable|date',
        'progress' => 'nullable|integer|min:0|max:100',
    ]);

    $epic->update($request->all());

    return redirect()->route('projects.epics', $project->id)
        ->with('success', 'Epic updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Project $project, Epic $epic)
{
    $epic->delete();

    return back()->with('success', 'Epic deleted successfully');
}
}
