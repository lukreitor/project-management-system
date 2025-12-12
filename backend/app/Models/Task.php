<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'completed',
        'difficulty',
        'project_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    protected $attributes = [
        'completed' => false,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
