<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_task(): void
    {
        $project = Project::factory()->create();

        $taskData = [
            'title' => 'New Task',
            'difficulty' => 'medium',
            'project_id' => $project->id,
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Task')
            ->assertJsonPath('data.difficulty', 'medium')
            ->assertJsonPath('data.completed', false);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'difficulty' => 'medium',
            'project_id' => $project->id,
            'completed' => false,
        ]);
    }

    public function test_cannot_create_task_without_title(): void
    {
        $project = Project::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'difficulty' => 'low',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_cannot_create_task_without_difficulty(): void
    {
        $project = Project::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Task without difficulty',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['difficulty']);
    }

    public function test_cannot_create_task_with_invalid_difficulty(): void
    {
        $project = Project::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Task',
            'difficulty' => 'invalid',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['difficulty']);
    }

    public function test_cannot_create_task_without_project_id(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Task',
            'difficulty' => 'low',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_cannot_create_task_with_non_existent_project(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Task',
            'difficulty' => 'low',
            'project_id' => 99999,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_can_toggle_task_completion(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

        $response->assertStatus(200)
            ->assertJsonPath('data.completed', true);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }

    public function test_can_toggle_completed_task_back_to_incomplete(): void
    {
        $task = Task::factory()->create(['completed' => true]);

        $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

        $response->assertStatus(200)
            ->assertJsonPath('data.completed', false);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false,
        ]);
    }

    public function test_can_delete_a_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Task deleted successfully');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_deleting_task_updates_project_progress(): void
    {
        $project = Project::factory()->create();
        $task1 = Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'low',
            'completed' => true
        ]);
        $task2 = Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'low',
            'completed' => false
        ]);

        $this->deleteJson("/api/tasks/{$task2->id}");

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.progress', 100.0);
    }
}
