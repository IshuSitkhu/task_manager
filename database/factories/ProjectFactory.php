<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\ProjectSetupService;
use App\Services\ProjectDemoDataService;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'created_by' => User::factory(),
        ];
    }

    // yo default status ra type ko lagi
    public function configure()
    {
        return $this->afterCreating(function (Project $project) {
            app(ProjectSetupService::class)->setup($project);

            app(ProjectDemoDataService::class)->setup($project);
        });
    }
}
