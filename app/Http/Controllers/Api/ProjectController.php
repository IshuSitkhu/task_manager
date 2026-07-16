<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $projects = Project::all();
        if (Auth::user()->role == 'project_manager') {
            $projects = Project::all();
        } else {
            $projects = Project::whereHas('members', function ($query) {
                $query->where('users.id', Auth::id());
            })->get();
        }

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => $request->user()->id,
        ]);

        if ($request->filled('members')) {
            $project->members()->sync($request->members);
        }

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project->load('members'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Project manager can view any project
        if (Auth::user()->role === 'project_manager') {
            return response()->json($project);
        }

        // Employee can only view projects they belong to
        if ($project->members()->where('users.id', Auth::id())->exists()) {
            return response()->json($project);
        }

        // User is not authorized
        return response()->json([
            'message' => 'You are not authorized to view this project.'
        ], 403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $project->members()->sync($request->members ?? []);

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project->load('members'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (Auth::user()->role !== 'project_manager') {
            return response()->json([
                'message' => 'You are not authorized to delete this project.'
            ], 403);
        }

        $project->delete();

        return response()-> json([
            'message' => 'Project deleted successfully',
        ]);
    }
}
