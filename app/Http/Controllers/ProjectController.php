<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index()
    {
        if (auth()->user()->role == 'project_manager') {
            $projects = Project::all();
        } else {
            $projects = Project::where('created_by', auth()->id())->get();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        if (auth()->user()->role !== 'project_manager') {
            abort(403);
        }

        $users = User::whereIn('role', ['employee', 'project_manager'])->get();

        return view('projects.create', compact('users'));
    }

    /**
     * Store project + default statuses
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'project_manager') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => auth()->id(),
        ]);

        //  DEFAULT STATUSES (FIXED)
        $defaultStatuses = [
            ['name' => 'Todo', 'slug' => 'todo', 'order' => 1, 'color' => '#6b7280'],
            ['name' => 'In Progress', 'slug' => 'in_progress', 'order' => 2, 'color' => '#3b82f6'],
            ['name' => 'Review', 'slug' => 'review', 'order' => 3, 'color' => '#a855f7'],
            ['name' => 'Done', 'slug' => 'done', 'order' => 4, 'color' => '#22c55e'],
        ];

        foreach ($defaultStatuses as $status) {
            $project->statuses()->create($status);
        }

        // members
        if ($request->members) {
            $project->members()->syncWithoutDetaching($request->members);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully');
    }

    public function storeStatus(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'color' => 'nullable',
        ]);

        $project->statuses()->create([
            'name' => $request->name,
            'slug' => $request->slug,
            'color' => $request->color,
            $order = ($project->statuses()->max('order') ?? 0) + 1,
        ]);

        return back();
    }

    /**
     * Show project details
     */
    public function show(Project $project)
    {
        $project->load('members', 'statuses');

        $allUsers = User::where('role', 'employee')->get();

        return view('projects.show', compact('project', 'allUsers'));
    }

    /**
     * Add members
     */
    public function addMembers(Request $request, Project $project)
    {
        $project->members()->syncWithoutDetaching($request->members);

        return back();
    }

    /**
     * Overview page
     */
    public function overview(Project $project)
    {
        $allUsers = User::all();

        return view('projects.overview', compact('project', 'allUsers'));
    }

    public function edit(Project $project) {}
    public function update(Request $request, Project $project) {}
    public function destroy(Project $project) {}
}
