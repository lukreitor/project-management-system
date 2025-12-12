<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'difficulty' => 'required|in:low,medium,high',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'data' => $task
        ], 201);
    }
}
