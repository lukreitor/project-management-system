<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProgressCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function __construct(
        private ProgressCalculationService $progressCalculationService
    ) {
    }
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::all();

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json([
            'data' => $project
        ], 201);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }
}
