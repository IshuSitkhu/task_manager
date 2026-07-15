<?php

namespace App\Services;

use App\Models\Epic;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;

class ProjectDemoDataService
{
    public function setup(Project $project): void
    {
        // Pick one random project manager
        $manager = User::where('role', 'project_manager')
            ->inRandomOrder()
            ->first();

        // Pick two random employees
        $employees = User::where('role', 'employee')
            ->inRandomOrder()
            ->limit(2)
            ->get();

        // Attach them to the project
        $project->members()->syncWithoutDetaching(
            $employees->pluck('id')
                ->push($manager->id)
                ->toArray()
        );

        // Create two sprints
        Sprint::factory()
            ->count(2)
            ->create([
                'project_id' => $project->id,
            ]);

        //epic
        $members = $project->members;

        Epic::factory()
            ->count(2)
            ->create([
                'project_id' => $project->id,
                'owner_id' => fn () => $members->random()->id,
            ]);

        //tasks
        // Refresh relationships
        $project->load('members', 'epics', 'sprints', 'statuses', 'taskTypes');

        Task::factory()
            ->count(10)
            ->state(fn () => [
                'project_id'  => $project->id,
                'epic_id'     => $project->epics->random()->id,
                'sprint_id'   => $project->sprints->random()->id,
                'assigned_to' => $project->members->random()->id,
                'status'      => $project->statuses->random()->slug,
                'type_id'     => $project->taskTypes->random()->id,
            ])
            ->create();

    }
}
