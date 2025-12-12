<?php

namespace App\Services;

use App\Models\Project;

class ProgressCalculationService
{
    private const EFFORT_POINTS = [
        'low' => 1,
        'medium' => 4,
        'high' => 12,
    ];

    public function calculateProgress(Project $project): float
    {
        $tasks = $project->tasks;

        if ($tasks->isEmpty()) {
            return 0.0;
        }

        $totalEffortPoints = 0;
        $completedEffortPoints = 0;

        foreach ($tasks as $task) {
            $effortPoints = $this->getEffortPoints($task->difficulty);
            $totalEffortPoints += $effortPoints;

            if ($task->completed) {
                $completedEffortPoints += $effortPoints;
            }
        }

        if ($totalEffortPoints === 0) {
            return 0.0;
        }

        return (float) round(($completedEffortPoints / $totalEffortPoints) * 100, 2);
    }

    private function getEffortPoints(string $difficulty): int
    {
        return self::EFFORT_POINTS[$difficulty] ?? 1;
    }
}
