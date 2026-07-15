<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Project::factory()->count(5)->create();
        Project::factory()
            ->count(5)
            ->state(fn () => [
                'created_by' => User::where('role', 'project_manager')
                    ->inRandomOrder()
                    ->value('id'),
            ])
            ->create();
    }
}
