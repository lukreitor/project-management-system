<?php

namespace App\Services;

use App\Models\Project;

/**
 * Service for calculating weighted project progress based on task difficulty.
 *
 * Uses an effort points system where:
 * - Low difficulty = 1 point
 * - Medium difficulty = 4 points
 * - High difficulty = 12 points
 *
 * Progress is calculated as the percentage of completed effort points
 * relative to total effort points across all tasks.
 */
class ProgressCalculationService
{
    /**
     * Effort points mapping for task difficulties.
     * Medium is 4x harder than low, high is 3x harder than medium.
     */
    private const EFFORT_POINTS = [
        'low' => 1,
        'medium' => 4,
        'high' => 12,
    ];

    /**
     * Calculate weighted progress percentage for a project.
     *
     * @param Project $project Project with loaded tasks relationship
     * @return float Progress percentage (0.0 to 100.0)
     */
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

    /**
     * Get effort points for a given difficulty level.
     *
     * @param string $difficulty Task difficulty (low, medium, high)
     * @return int Effort points (defaults to 1 for invalid difficulty)
     */
    private function getEffortPoints(string $difficulty): int
    {
        return self::EFFORT_POINTS[$difficulty] ?? 1;
    }
}
