<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\User;
use App\Models\Sprint;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SprintTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_project_manager_can_create_a_sprint(): void
    {
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $response = $this->post(route('projects.sprints.store', $project),
        [
            'name' => 'sprint1',
            'goal' => 'login sprint',
            'start_date' =>'2026-07-14',
            'end_date' => '2026-08-30',
            'status' =>  'active',
            'progress' => 0,
        ]);

        $response->assertRedirect(route('projects.sprints', $project));

        $this->assertDatabaseHas('sprints',[
            'project_id' => $project->id,
            'name' => 'sprint1',
        ]);
    }

    public function test_project_manager_can_update_a_sprint():void
    {
        $manager = User::factory()->create([
            'role'=> 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $sprint = Sprint::create([
            'project_id' => $project->id,
            'name' => 'Old Sprint',
            'goal' => 'login sprint',
            'status' => 'active',
            'start_date' => '2026-07-15',
            'end_date' => '2026-07-25',
            'progress' => 0,
        ]);

         // Act
        $response = $this->put(route('projects.sprints.update', [
                'project' => $project,
                'sprint' => $sprint,
            ]),
            [
                'name' => 'Updated Sprint',
                'goal' => 'login sprint',
                'status' => 'planned',
                'start_date' => '2026-07-15',
                'end_date' => '2026-07-30',
                'progress' => 50,
            ]
        );
        // Assert
        $response->assertRedirect(route('projects.sprints', $project));

        $this->assertDatabaseHas('sprints', [
            'name' => 'Updated Sprint',
            'status' => 'planned',
        ]);
    }

    public function test_project_manager_can_delete_a_sprint():void
    {
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        $project = Project::factory()->create([
            'created_by' => $manager->id,
        ]);

        $sprint = Sprint::create([
            'project_id' => $project->id,
            'name' => 'sprint1',
            'goal' => 'login sprint',
            'status' => 'active',
            'progress' => 0,
        ]);
        // Act
        $response = $this->delete(
            route('projects.sprints.destroy', [
                'project' => $project,
                'sprint' => $sprint,
            ])
        );
        // Assert
        $response->assertRedirect();

        $this->assertDatabaseMissing('sprints', [
            'name' => 'sprint1',
        ]);
    }

}
