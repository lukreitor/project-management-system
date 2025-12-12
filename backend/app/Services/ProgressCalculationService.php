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
        return 0.0;
    }

    private function getEffortPoints(string $difficulty): int
    {
        return self::EFFORT_POINTS[$difficulty] ?? 1;
    }
}
