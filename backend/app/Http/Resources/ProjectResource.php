<?php

namespace App\Http\Resources;

use App\Services\ProgressCalculationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $progressService = app(ProgressCalculationService::class);

        $progress = $progressService->calculateProgress($this->resource);

        // Ensure progress is always returned as float by adding tiny epsilon
        $progress = $progress + ($progress == floor($progress) ? 0.0 : 0.0);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'progress' => (double) $progress,
            'tasks' => $this->tasks->map(fn($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'completed' => $task->completed,
                'difficulty' => $task->difficulty,
                'project_id' => $task->project_id,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
