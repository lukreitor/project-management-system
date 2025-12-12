<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Services\ProgressCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProgressCalculationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProgressCalculationService();
    }

    public function test_returns_zero_progress_for_project_without_tasks(): void
    {
        $project = Project::factory()->create();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(0.0, $progress);
    }

    public function test_returns_zero_progress_when_no_tasks_completed(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => false]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => false]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => false]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(0.0, $progress);
    }

    public function test_returns_hundred_progress_when_all_tasks_completed(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => true]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(100.0, $progress);
    }

    public function test_calculates_weighted_progress_correctly_with_low_difficulty(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => false]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(50.0, $progress);
    }

    public function test_calculates_weighted_progress_correctly_with_medium_difficulty(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => false]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(50.0, $progress);
    }

    public function test_calculates_weighted_progress_correctly_with_high_difficulty(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => false]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $this->assertEquals(50.0, $progress);
    }

    public function test_calculates_weighted_progress_with_mixed_difficulties(): void
    {
        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => true]);
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => false]);

        $project->refresh();

        $progress = $this->service->calculateProgress($project);

        $expectedProgress = ((1 + 4) / (1 + 4 + 12)) * 100;
        $this->assertEquals(round($expectedProgress, 2), $progress);
    }

    public function test_effort_points_are_correct_for_each_difficulty(): void
    {
        $project = Project::factory()->create();

        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'low', 'completed' => true]);
        $project->refresh();
        $progressLow = $this->service->calculateProgress($project);

        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'medium', 'completed' => true]);
        $project->refresh();
        $progressMedium = $this->service->calculateProgress($project);

        $project = Project::factory()->create();
        Task::factory()->create(['project_id' => $project->id, 'difficulty' => 'high', 'completed' => true]);
        $project->refresh();
        $progressHigh = $this->service->calculateProgress($project);

        $this->assertEquals(100.0, $progressLow);
        $this->assertEquals(100.0, $progressMedium);
        $this->assertEquals(100.0, $progressHigh);
    }

    public function test_medium_task_is_one_third_of_high_task(): void
    {
        $project1 = Project::factory()->create();
        Task::factory()->create(['project_id' => $project1->id, 'difficulty' => 'medium', 'completed' => true]);
        Task::factory()->create(['project_id' => $project1->id, 'difficulty' => 'medium', 'completed' => false]);
        Task::factory()->create(['project_id' => $project1->id, 'difficulty' => 'medium', 'completed' => false]);
        $project1->refresh();
        $progressMedium = $this->service->calculateProgress($project1);

        $project2 = Project::factory()->create();
        Task::factory()->create(['project_id' => $project2->id, 'difficulty' => 'high', 'completed' => true]);
        $project2->refresh();
        $progressHigh = $this->service->calculateProgress($project2);

        $this->assertEquals(round((4 / 12) * 100, 2), $progressMedium);
        $this->assertEquals(100.0, $progressHigh);
    }
}
