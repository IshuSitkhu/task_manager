<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityService;
use App\Events\ProjectAssigned;

class CreateProjectAction
{
    public function execute(Request $request, ActivityService $activityService): Project
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

        // Default statuses
        $defaultStatuses = [
            ['name' => 'Todo', 'slug' => 'todo', 'order' => 1, 'color' => '#6b7280'],
            ['name' => 'In Progress', 'slug' => 'in_progress', 'order' => 2, 'color' => '#3b82f6'],
            ['name' => 'Review', 'slug' => 'review', 'order' => 3, 'color' => '#a855f7'],
            ['name' => 'Bug', 'slug' => 'bug', 'order' => 4, 'color' => '#ef4444'],
            ['name' => 'Done', 'slug' => 'done', 'order' => 5, 'color' => '#22c55e'],
        ];

        foreach ($defaultStatuses as $status) {
            $project->statuses()->create($status);
        }

        if ($request->members) {

            $project->members()->syncWithoutDetaching($request->members);

            event(new ProjectAssigned($project, $request->members));
        }

        $defaultTypes = [
            ['name' => 'Feature', 'slug' => 'feature', 'color' => '#3b82f6'],
            ['name' => 'UI', 'slug' => 'ui', 'color' => '#a855f7'],
            ['name' => 'Bug', 'slug' => 'bug', 'color' => '#ef4444'],
            ['name' => 'Backend', 'slug' => 'backend', 'color' => '#22c55e'],
            ['name' => 'Test', 'slug' => 'test', 'color' => '#f59e0b'],
        ];

        foreach ($defaultTypes as $type) {
            $project->taskTypes()->create($type);
        }

        return $project;
    }
}
