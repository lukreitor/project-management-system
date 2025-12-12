<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{

    /**
     * Get all projects with their tasks and calculated progress.
     *
     * @return AnonymousResourceCollection Collection of projects ordered by newest first
     */
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::with('tasks')
            ->latest()
            ->get();

        return ProjectResource::collection($projects);
    }

    /**
     * Create a new project.
     *
     * @param StoreProjectRequest $request Validated request containing project name
     * @return JsonResponse Created project data with 201 status
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json([
            'data' => $project
        ], 201);
    }

    /**
     * Get a single project with its tasks and calculated progress.
     *
     * @param Project $project The project model instance (route model binding)
     * @return ProjectResource Project data with tasks and weighted progress
     */
    public function show(Project $project): ProjectResource
    {
        $project->load('tasks');

        return new ProjectResource($project);
    }

    /**
     * Delete a project and all its associated tasks.
     *
     * @param Project $project The project model instance (route model binding)
     * @return JsonResponse Empty response with 204 status
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
