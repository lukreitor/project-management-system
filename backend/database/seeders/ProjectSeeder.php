<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $project1 = Project::create([
            'name' => 'Website Redesign',
        ]);

        Task::create([
            'title' => 'Create wireframes',
            'difficulty' => 'low',
            'completed' => true,
            'project_id' => $project1->id,
        ]);

        Task::create([
            'title' => 'Design homepage mockup',
            'difficulty' => 'medium',
            'completed' => true,
            'project_id' => $project1->id,
        ]);

        Task::create([
            'title' => 'Implement responsive layout',
            'difficulty' => 'high',
            'completed' => false,
            'project_id' => $project1->id,
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App Development',
        ]);

        Task::create([
            'title' => 'Set up project structure',
            'difficulty' => 'low',
            'completed' => true,
            'project_id' => $project2->id,
        ]);

        Task::create([
            'title' => 'Implement authentication',
            'difficulty' => 'high',
            'completed' => false,
            'project_id' => $project2->id,
        ]);

        Task::create([
            'title' => 'Create user profile screen',
            'difficulty' => 'medium',
            'completed' => false,
            'project_id' => $project2->id,
        ]);

        Task::create([
            'title' => 'Add push notifications',
            'difficulty' => 'high',
            'completed' => false,
            'project_id' => $project2->id,
        ]);

        $project3 = Project::create([
            'name' => 'API Integration',
        ]);

        Task::create([
            'title' => 'Research API documentation',
            'difficulty' => 'low',
            'completed' => true,
            'project_id' => $project3->id,
        ]);

        Task::create([
            'title' => 'Implement API client',
            'difficulty' => 'medium',
            'completed' => true,
            'project_id' => $project3->id,
        ]);

        Task::create([
            'title' => 'Add error handling',
            'difficulty' => 'medium',
            'completed' => true,
            'project_id' => $project3->id,
        ]);

        Task::create([
            'title' => 'Write unit tests',
            'difficulty' => 'low',
            'completed' => true,
            'project_id' => $project3->id,
        ]);
    }
}
