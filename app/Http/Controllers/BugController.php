<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bug;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityService;

class BugController extends Controller
{
    public function index(Project $project)
    {
        $query = Bug::where('project_id', $project->id)
            ->with(['task', 'assignee']);

        // Employees see only bugs assigned to them
        if (Auth::user()->role == 'employee') {
            $query->where('assigned_to', Auth::id());
        }

        $bugs = $query->latest()->get();

        $users = $project->members;

        return view('bugs.index', compact(
            'project',
            'bugs',
            'users'
        ));
    }

    public function store(Request $request, Project $project, ActivityService $activityService)
    {
        $request->validate([
            'title' => 'required',
            'severity' => 'required',
            'status' => 'required|in:open,in_progress,fixed',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bugs', 'public');
        }

        Bug::create([
            'project_id' => $project->id,
            'task_id' => $request->task_id,
            'title' => $request->title,
            'description' => $request->description,
            'severity' => $request->severity,
            'image' => $imagePath,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
        ]);

        $activityService->log(
            Auth::user(),
            'created_bug',
            'Created bug "' . $request->title . '"',
            null
        );

        return back()->with('success', 'Bug reported successfully');
    }

    public function taskBugs(Project $project, Task $task)
    {
        $bugs = $task->bugs()->latest()->get();

        return view('bugs.partials.list', compact('bugs'));
    }

    public function edit(Project $project, Bug $bug)
    {
        $tasks = Task::where('project_id', $project->id)->get();

        $users = $project->members;

        return view('bugs.edit', compact(
            'project',
            'bug',
            'tasks',
            'users'
        ));
    }

public function update(Request $request, Project $project, Bug $bug)
{
    $request->validate([
        'title' => 'required',
        'severity' => 'required',
        'status' => 'required|in:open,in_progress,fixed',
    ]);

    if ($request->hasFile('image')) {

        if ($bug->image) {
            Storage::delete('public/' . $bug->image);
        }

        $bug->image = $request->file('image')
            ->store('bugs', 'public');
    }

    $bug->update([
        'task_id' => $request->task_id,
        'title' => $request->title,
        'description' => $request->description,
        'severity' => $request->severity,
        'assigned_to' => $request->assigned_to,
        'status' => $request->status,
        'image' => $bug->image,
    ]);

    return redirect()
        ->route('projects.bugs.index', $project)
        ->with('success', 'Bug updated successfully.');
}

    public function destroy(Project $project, Bug $bug)
    {
        if ($bug->image && Storage::exists('public/' . $bug->image)) {
            Storage::delete('public/' . $bug->image);
        }

        $bug->delete();

        return back()->with('success', 'Bug deleted successfully.');
    }
}
