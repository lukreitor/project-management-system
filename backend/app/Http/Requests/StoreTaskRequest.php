<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'difficulty' => 'required|in:low,medium,high',
            'project_id' => 'required|exists:projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.string' => 'The task title must be a string.',
            'title.max' => 'The task title must not exceed 255 characters.',
            'difficulty.required' => 'The task difficulty is required.',
            'difficulty.in' => 'The task difficulty must be low, medium, or high.',
            'project_id.required' => 'The project ID is required.',
            'project_id.exists' => 'The specified project does not exist.',
        ];
    }
}
