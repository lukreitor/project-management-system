<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Create a new task.
     *
     * @param StoreTaskRequest $request Validated request with title, difficulty, and project_id
     * @return JsonResponse Created task data with 201 status
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return response()->json([
            'data' => $task
        ], 201);
    }

    /**
     * Toggle task completion status.
     *
     * Toggles the completed field between true and false.
     * Updates the project's weighted progress calculation automatically.
     *
     * @param Task $task The task model instance (route model binding)
     * @return JsonResponse Updated task data with new completion status
     */
    public function toggle(Task $task): JsonResponse
    {
        $task->completed = !$task->completed;
        $task->save();

        return response()->json([
            'data' => $task
        ]);
    }

    /**
     * Delete a task.
     *
     * Permanently removes the task from the database.
     * Updates the project's weighted progress calculation automatically.
     *
     * @param Task $task The task model instance (route model binding)
     * @return JsonResponse Success message
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
