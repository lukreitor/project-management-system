<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
