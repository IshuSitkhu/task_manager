<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bug;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class BugController extends Controller
{
    public function index(Project $project)
    {
        $bugs = Bug::where('project_id', $project->id)->get();

        return view('bugs.index', compact('project', 'bugs'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required',
            'severity' => 'required',
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
            'status' => 'open',
        ]);

        return back()->with('success', 'Bug reported successfully');
    }

    public function taskBugs(Project $project, Task $task)
    {
        $bugs = $task->bugs()->latest()->get();

        return view('bugs.partials.list', compact('bugs'));
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
