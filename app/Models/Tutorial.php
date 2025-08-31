<?php

namespace App\Models;

class Tutorial extends Content
{
    protected $attributes = [
        'type' => 'tutorial',
        'status' => 'draft'
    ];

    protected $fillable = [
        'title',
        'body',
        'author_id',
        'author_name',
        'published_at',
        'tags',
        'status',
        'difficulty_level',
        'estimated_duration',
        'prerequisites',
        'learning_objectives',
        'steps'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'prerequisites' => 'array',
        'learning_objectives' => 'array',
        'steps' => 'array',
        'estimated_duration' => 'integer'
    ];

    public function getContentType()
    {
        return 'tutorial';
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopeByDuration($query, $maxMinutes)
    {
        return $query->where('estimated_duration', '<=', $maxMinutes);
    }

    public function getDifficultyBadgeClass()
    {
        return match ($this->difficulty_level) {
            'beginner' => 'badge-success',
            'intermediate' => 'badge-warning',
            'advanced' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getFormattedDuration()
    {
        if (!$this->estimated_duration) {
            return 'Duration not specified';
        }

        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;

        if ($hours > 0) {
            return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
        }

        return $minutes . 'm';
    }

    public function getStepCount()
    {
        return count($this->steps ?? []);
    }
}