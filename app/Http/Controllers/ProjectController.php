<?php

namespace App\Http\Controllers;
use App\Models\Notification;

use App\Models\Project;
use App\Models\User;
use App\Services\ActivityService;
use App\Actions\CreateProjectAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ProjectAssigned;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index()
    {
        if (Auth::user()->role == 'project_manager') {

            $projects = Project::all();

        } else {

            $projects = Project::whereHas('members', function ($query) {
                $query->where('users.id', Auth::id());
            })->get();

        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Overview page ( Open Project → click gare paxi)
     */
    public function overview(Project $project)
    {
        // selected memebers are not shown in options
        $allUsers = User::whereNotIn('id', $project->members()->pluck('users.id'))->get();

        return view('projects.overview', compact('project', 'allUsers'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'project_manager') {
            abort(403);
        }

        $users = User::whereIn('role', ['employee', 'project_manager'])->get();

        return view('projects.create', compact('users'));
    }

    public function store(ProjectRequest $request, CreateProjectAction $createProjectAction, ActivityService $activityService)
    {

        $createProjectAction->execute($request, $activityService);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully');
    }

    public function edit(Project $project) {
        if (Auth::user()->role !== 'project_manager') {
            abort(403);
        }

        $users = User::whereIn('role', ['employee', 'project_manager'])->get();

        return view('projects.edit', compact('project', 'users'));
    }


    public function update(ProjectRequest $request, Project $project, ActivityService $activityService)
    {
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // members
        if ($request->members) {
            $project->members()->sync($request->members);
        } else {
            $project->members()->sync([]);
        }

       $activityService->projectUpdated( Auth::user(), $project);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project, ActivityService $activityService ) {
        $title = $project-> title;

        $activityService->log(
            Auth::user(),
            'Deleted_Project',
            "Deleted project '{$project->name}'",
            $project,
            "Project"
        );
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }

    public function storeStatus(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'color' => 'nullable',
        ]);

        $exists = $project->statuses()
            ->where('slug', $request->slug)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Status already exists!');
        }

        $project->statuses()->create([
            'name' => $request->name,
            'slug' => $request->slug,
            'color' => $request->color,
            'order' => ($project->statuses()->max('order') ?? 0) + 1,
        ]);

        return back();
    }

    public function destroyStatus(Project $project, int $statusId)
    {
        $status = $project->statuses()->findOrFail($statusId);

        $taskCount = $project->tasks()
            ->where('status', $status->slug)
            ->count();

        if ($taskCount > 0) {
            return back()->with(
                'error',
                'Cannot delete status because it contains tasks.'
            );
        }

        $status->delete();

        return back()->with(
            'success',
            'Status deleted successfully.'
        );
    }

    public function storeType(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'color' => 'nullable',
        ]);

        $exists = $project->taskTypes()
                        ->where('name', $request->name)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Type already exists.');
        }

        $project->taskTypes()->create([
            'name' => $request->name,
            'slug' => $request->slug,
            'color' => $request->color,

        ]);

        return back()->with('success', 'Type created.');
    }

    public function destroyType(Project $project, $typeId)
    {
        $type = $project->taskTypes()->findOrFail($typeId);

        $count = $project->tasks()
                        ->where('type_id', $type->id)
                        ->count();

        if ($count > 0) {
            return back()->with('error', 'Type contains tasks.');
        }

        $type->delete();

        return back()->with('success', 'Type deleted.');
    }

    /**
     * Add members
     */
    public function addMembers(Request $request, Project $project, ActivityService $activityService)
    {
        // add memebers without detaching existing ones
        $project->members()->syncWithoutDetaching($request->members);

        $members = User::whereIn('id', $request->members)->get();

        foreach ($members as $member) {
            $activityService->memberAdded( Auth::user(), $project, $member );
        }

        return back();
    }

    public function removeMember(Project $project, User $user, ActivityService $activityService)
    {

        $activityService->memberRemoved( Auth::user(), $project, $user);

        $project->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }


}
