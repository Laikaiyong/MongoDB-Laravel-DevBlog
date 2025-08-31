<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

abstract class Content extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'contents';
    
    protected $fillable = [
        'title',
        'body',
        'author_id',
        'author_name',
        'published_at',
        'tags',
        'status',
        'type'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    protected $attributes = [
        'status' => 'draft'
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    abstract public function getContentType();
}