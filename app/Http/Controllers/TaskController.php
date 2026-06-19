<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Epic;
use App\Models\Sprint;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)
            ->with(['epic', 'sprint', 'assignee'])
            ->latest()
            ->get();

        return view('tasks.index', compact('project', 'tasks'));
    }

    public function create(Project $project)
    {
        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members; // only project members

        return view('tasks.create', compact('project', 'epics', 'sprints', 'users'));
    }

    public function store(Request $request, Project $project)
    {


        $request->validate([
            'title' => 'required|string|max:255',
            'epic_id' => 'required|exists:epics,id',
            'sprint_id' => 'nullable|exists:sprints,id',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required',
            'priority' => 'required',
            'type' => 'required',
        ]);

        Task::create([
            'project_id' => $project->id,
            'epic_id' => $request->epic_id,
            'sprint_id' => $request->sprint_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'type' => $request->type,
            'github_link' => $request->github_link,
            'due_date' => $request->due_date,
        ]);


        // return redirect()->route('projects.tasks', $project->id)
        //     ->with('success', 'Task created successfully');

        return redirect()->back()
            ->with('success', 'Task created successfully');
    }

    public function edit(Project $project, Task $task)
    {
        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members;

        return view('tasks.edit', compact('project', 'task', 'epics', 'sprints', 'users'));
    }

    public function editmodule(Project $project, Task $task)
    {
        //IT RETURN HTML FORM
        return view('tasks.partials.edit-modal-form', [
            'project' => $project,
            'task' => $task,
            'epics' => $project->epics,
            'sprints' => $project->sprints,
            'users' => $project->members,
        ]);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'epic_id' => 'required|exists:epics,id',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required',
            'priority' => 'required',
            'type' => 'required',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'epic_id' => $request->epic_id,
            'sprint_id' => $request->sprint_id,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'type' => $request->type,
            'github_link' => $request->github_link,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()
            ->with('success', 'Task updated successfully');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully');
    }



    public function board(Project $project)
    {
        $project->load('statuses');

        $tasks = $project->tasks()->with('epic', 'assignee', 'projectStatus')->latest()->get();

        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = User::where('role', 'employee')->get();

        return view('tasks.board', compact(
            'project',
            'tasks',
            'epics',
            'sprints',
            'users'
        ));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }


    public function moveStatus(Request $request, Task $task)
    {
        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
