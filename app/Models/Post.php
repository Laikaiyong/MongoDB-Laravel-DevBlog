<?php

namespace App\Models;

class Post extends Content
{
    protected $attributes = [
        'type' => 'post',
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
        'excerpt',
        'reading_time'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'reading_time' => 'integer'
    ];

    public function getContentType()
    {
        return 'post';
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: substr(strip_tags($this->body), 0, 150) . '...';
    }

    public function getReadingTimeAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $wordCount = str_word_count(strip_tags($this->body));
        return ceil($wordCount / 200);
    }
}