<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityService;
use App\Services\ProjectSetupService;
use App\Events\ProjectAssigned;

class CreateProjectAction
{
    public function execute(Request $request, ActivityService $activityService, ProjectSetupService $projectSetupService): Project
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        $activityService->projectCreated(Auth::user(), $project);

        $projectSetupService->setup($project);

        if ($request->members) {

            $project->members()->syncWithoutDetaching($request->members);

            event(new ProjectAssigned($project, $request->members));
        }



        return $project;
    }
}
