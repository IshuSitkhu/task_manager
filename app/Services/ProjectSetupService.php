<?php

namespace App\Services;

use App\Models\Project;

class ProjectSetupService
{
    public function setup(Project $project): void
    {
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
    }
}
