<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\User;
use App\Models\Epic;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpicTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_project_manager_can_create_an_epic(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        // In controller, owner comes from $project->members.
        // So attach the owner to the project.
        $owner = User::factory()->create();

        $project->members()->attach($owner->id);

        // Act
        $response = $this->post(
            route('projects.epics.store', $project),
            [
                'title' => 'Authentication Module',
                'description' => 'Login system',
                'owner_id' => $owner->id,
                'priority' => 'high',
                'status' => 'not_started',
                'planned_start_date' => '2026-07-15',
                'planned_end_date' => '2026-07-25',
                'progress' => 0,
            ]
        );

        // Assert
        $response->assertRedirect(route('projects.epics', $project));

        $this->assertDatabaseHas('epics', [
            'project_id' => $project->id,
            'title' => 'Authentication Module',
            'owner_id' => $owner->id,
        ]);
    }

    public function test_epic_title_is_required(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $owner = User::factory()->create();

        $project->members()->attach($owner->id);

        // Act
        $response = $this->post(route('projects.epics.store', $project), [
            'title' => '',
            'description' => 'Test epic',
            'owner_id' => $owner->id,
            'priority' => 'high',
            'status' => 'not_started',
            'planned_start_date' => '2026-07-15',
            'planned_end_date' => '2026-07-25',
            'progress' => 0,
        ]);

        // Assert
        $response->assertSessionHasErrors([
            'title' => 'Epic title is required.',
        ]);

        $this->assertDatabaseMissing('epics', [
            'description' => 'Test epic',
        ]);
    }


    public function test_epic_owner_must_exist(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        // Act
        $response = $this->post(route('projects.epics.store', $project), [
            'title' => 'Authentication Module',
            'description' => 'Login system',

            // This user does not exist
            'owner_id' => 99999,

            'priority' => 'high',
            'status' => 'not_started',
            'planned_start_date' => '2026-07-15',
            'planned_end_date' => '2026-07-25',
            'progress' => 0,
        ]);

        // Assert
        $response->assertSessionHasErrors([
            'owner_id',
        ]);

        $this->assertDatabaseMissing('epics', [
            'title' => 'Authentication Module',
        ]);
    }


    public function test_project_manager_can_update_an_epic(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $owner = User::factory()->create();

        $project->members()->attach($owner->id);

        $epic = Epic::create([
            'project_id' => $project->id,
            'title' => 'Old Epic',
            'description' => 'Old description',
            'owner_id' => $owner->id,
            'priority' => 'medium',
            'status' => 'not_started',
            'planned_start_date' => '2026-07-15',
            'planned_end_date' => '2026-07-25',
            'progress' => 0,
        ]);

        // Act
        $response = $this->put(route('projects.epics.update', [
                'project' => $project,
                'epic' => $epic,
            ]),
            [
                'title' => 'Updated Epic',
                'description' => 'Updated description',
                'owner_id' => $owner->id,
                'priority' => 'high',
                'status' => 'in_progress',
                'planned_start_date' => '2026-07-15',
                'planned_end_date' => '2026-07-30',
                'progress' => 50,
            ]
        );
        // Assert
        $response->assertRedirect(route('projects.epics', $project));

        $this->assertDatabaseHas('epics', [
            'title' => 'Updated Epic',
            'status' => 'in_progress',
        ]);
    }

    public function test_project_manager_can_delete_an_epic(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $owner = User::factory()->create();

        $project->members()->attach($owner->id);


        $epic = Epic::create([
            'project_id' => $project->id,
            'title' => 'Delete Me Epic',
            'description' => 'Testing delete',
            'owner_id' => $owner->id,
            'priority' => 'low',
            'status' => 'not_started',
            'progress' => 0,
        ]);
        // Act
        $response = $this->delete(
            route('projects.epics.destroy', [
                'project' => $project,
                'epic' => $epic,
            ])
        );
        // Assert
        $response->assertRedirect();

        $this->assertDatabaseMissing('epics', [
            'title' => 'Delete Me Epic',
        ]);
    }


}
