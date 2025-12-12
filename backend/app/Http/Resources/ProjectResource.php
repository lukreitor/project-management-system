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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'progress' => $progressService->calculateProgress($this->resource),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
