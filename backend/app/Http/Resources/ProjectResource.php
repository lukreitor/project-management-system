<?php

namespace App\Http\Resources;

use App\Services\ProgressCalculationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $progressService = app(ProgressCalculationService::class);
        $this->resource->load('tasks');

        $progress = $progressService->calculateProgress($this->resource);

        // Ensure progress is always returned as float by adding tiny epsilon
        $progress = $progress + ($progress == floor($progress) ? 0.0 : 0.0);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'progress' => (double) $progress,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
