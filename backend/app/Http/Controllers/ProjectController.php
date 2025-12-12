<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProgressCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private ProgressCalculationService $progressCalculationService
    ) {
    }
    public function index(): JsonResponse
    {
        $projects = Project::all();

        return response()->json([
            'data' => $projects
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'data' => $project
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load('tasks');

        $progress = $this->progressCalculationService->calculateProgress($project);

        return response()->json([
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'progress' => $progress,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
            ]
        ]);
    }
}
