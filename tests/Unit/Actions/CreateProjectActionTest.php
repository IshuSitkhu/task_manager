<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Models\User;
use App\Actions\CreateProjectAction;
use App\Services\ActivityService;
use App\Services\ProjectSetupService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProjectActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_project_action_creates_project(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $request = new Request([
            'name' => 'School Management',
            'description' => 'Testing Action',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
        ]);

        $action = app(CreateProjectAction::class);
        $activityService = app(ActivityService::class);
        $projectSetupService = app(ProjectSetupService::class);

        // Act
        $action->execute($request, $activityService, $projectSetupService);

        // Assert
        $this->assertDatabaseHas('projects', [
            'name' => 'School Management',
        ]);
    }
}
