<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ProjectTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example. Refreshdatabase: Every time you run a test: Laravel creates a fresh test database. Runs your migrations. Executes the test. Cleans everything up
     */
    // public function test_project_manager_can_create_a_project(): void
    // {
    //     // Arrange
    //     $manager = User::factory()->create([
    //         'role' => 'project_manager',
    //     ]);

    //     //behaves as if loginuser
    //     $this->actingAs($manager);

    //     // Act
    //     $response = $this->post(route('projects.store'), [
    //         'name' => 'School Management',
    //         'description' => 'My first project',
    //         'status' => 'active',
    //         'start_date' => '2026-07-14',
    //         'end_date' => '2026-08-30',
    //     ]);

    //     // Assert Redirect to projects page
    //     $response->assertRedirect(route('projects.index'));

    //     // Project exists in database
    //     $this->assertDatabaseHas('projects', [
    //         'name' => 'School Management',
    //     ]);
    // }

    public static function validProjects(): array
    {
        return [
            'School Project' => [
                'School Management',
                'School system',
                'active',
                '2026-07-14',
                '2026-08-30',
            ],

            'Hospital Project' => [
                'Hospital Management',
                'Hospital system',
                'completed',
                '2026-08-01',
                '2026-09-15',
            ],

            'Inventory Project' => [
                'Inventory System',
                'Inventory tracking',
                'archived',
                '2026-09-01',
                '2026-10-01',
            ],
        ];
    }

    #[DataProvider('validProjects')]
    public function test_project_manager_can_create_a_project(
        string $name,
        string $description,
        string $status,
        string $startDate,
        string $endDate
    ): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        // Act
        $response = $this->post(route('projects.store'), [
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Assert
        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'name' => $name,
            'description' => $description,
            'status' => $status,
        ]);
    }

    public function test_employee_cannot_create_a_project(): void
    {
        // Arrange
        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $this->actingAs($employee);

        // Act
        $response = $this->post(route('projects.store'), [
            'name' => 'Secret Project',
            'description' => 'Should not be created',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
        ]);

        // Assert HTTP 403 Forbidden
        $response->assertForbidden();

        // Project was NOT created
        $this->assertDatabaseMissing('projects', [
            'name' => 'Secret Project',
        ]);
    }

    public function test_project_manager_can_update_a_project(): void
    {
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        // Create existing project
        $project = \App\Models\Project::create([
            'name' => 'Old Project',
            'description' => 'Old description',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
            'created_by' => $manager->id,
        ]);


        // Act
        $response = $this->put(route('projects.update', $project), [
            'name' => 'Updated Project',
            'description' => 'Updated description',
            'status' => 'completed',
            'start_date' => '2026-07-20',
            'end_date' => '2026-09-01',
        ]);


        // Assert redirect
        $response->assertRedirect(route('projects.index'));

        // Assert database updated
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project',
            'description' => 'Updated description',
            'status' => 'completed',
        ]);
    }

    public function test_project_manager_can_delete_a_project(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);


        // Create project that we want to delete
        $project = \App\Models\Project::create([
            'name' => 'Project To Delete',
            'description' => 'Delete testing project',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
            'created_by' => $manager->id,
        ]);


        // Act
        $response = $this->delete(route('projects.destroy', $project));


        // Assert redirect
        $response->assertRedirect(route('projects.index'));


        // Assert project is deleted
        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => 'Project To Delete',
        ]);
    }

    public function test_employee_cannot_update_a_project(): void
    {
        // Arrange
        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $this->actingAs($employee);

        // Existing project
        $project = \App\Models\Project::create([
            'name' => 'Old Project',
            'description' => 'Old description',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
            'created_by' => $employee->id,
        ]);

        // Act
        $response = $this->put(route('projects.update', $project), [
            'name' => 'Updated Project',
            'description' => 'Updated description',
            'status' => 'completed',
            'start_date' => '2026-07-20',
            'end_date' => '2026-09-01',
        ]);

        // Assert
        $response->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Old Project',
            'description' => 'Old description',
            'status' => 'active',
        ]);
    }

    public function test_employee_cannot_delete_a_project(): void
    {
        // Arrange
        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $this->actingAs($employee);


        // Create project that we want to delete
        $project = \App\Models\Project::create([
            'name' => 'Project To Delete',
            'description' => 'Delete testing project',
            'status' => 'active',
            'start_date' => '2026-07-14',
            'end_date' => '2026-08-30',
            'created_by' => $employee->id,
        ]);


        // Act
        $response = $this->delete(route('projects.destroy', $project));


        // Assert redirect
        $response->assertForbidden();


        // Assert project is deleted
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Project To Delete',
        ]);
    }

    public static function invalidProjectNames(): array
    {
        return [
            'Name is required' => [
                '',
                'Please enter a project name.',
            ],

            'Less than 3 characters' => [
                'AB',
                'The project name must be at least 3 characters.',
            ],

            'More than 255 characters' => [
                str_repeat('A', 256),
                'The project name may not be greater than 255 characters.',
            ],

            'Starts with number' => [
                '123 Project',
                'Project names must start with a letter and may only contain letters, numbers, spaces, hyphens (-), and underscores (_).',
            ],
        ];
    }

    #[DataProvider('invalidProjectNames')]
    public function test_project_name_validation(
        string $name,
        string $expectedMessage
    ): void {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        // Act
        $response = $this->post(route('projects.store'), [
            'name' => $name,
            'description' => 'Test project',
            'status' => 'active',
        ]);

        // Assert
        $response->assertSessionHasErrors([
            'name' => $expectedMessage,
        ]);

        $this->assertDatabaseMissing('projects', [
            'description' => 'Test project',
        ]);
    }

    public function test_project_status_is_required(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'role' => 'project_manager',
        ]);

        $this->actingAs($manager);

        // Act
        $response = $this->post(route('projects.store'), [
            'name' => 'School Management',
            'description' => 'Test project',
            'status' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors([
            'status' => 'Please select a project status.',
        ]);
    }

}
