<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'completed' => false,
            'difficulty' => $this->faker->randomElement(['low', 'medium', 'high']),
            'project_id' => Project::factory(),
        ];
    }
}
