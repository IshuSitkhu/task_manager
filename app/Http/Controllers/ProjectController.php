<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role !== 'project_manager') {
            abort(403);
        }

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
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

            Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('projects.index');
        }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
