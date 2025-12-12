<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_projects(): void
    {
        Project::factory()->count(3)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_a_project(): void
    {
        $projectData = [
            'name' => 'New Project',
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'New Project');

        $this->assertDatabaseHas('projects', ['name' => 'New Project']);
    }

    public function test_cannot_create_project_without_name(): void
    {
        $response = $this->postJson('/api/projects', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_create_project_with_name_exceeding_max_length(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_project_with_progress(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'low',
            'completed' => true
        ]);
        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'low',
            'completed' => false
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'progress',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.progress', 50.0);
    }

    public function test_project_without_tasks_has_zero_progress(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.progress', 0.0);
    }

    public function test_project_with_all_completed_tasks_has_hundred_progress(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(3)->create([
            'project_id' => $project->id,
            'completed' => true
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.progress', 100.0);
    }

    public function test_list_projects_includes_progress(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'high',
            'completed' => true
        ]);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'progress',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}
