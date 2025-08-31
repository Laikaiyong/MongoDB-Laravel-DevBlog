<?php

namespace App\Models;

class Article extends Content
{
    protected $attributes = [
        'type' => 'article',
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
        'category',
        'featured_image',
        'seo_title',
        'seo_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array'
    ];

    public function getContentType()
    {
        return 'article';
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFeatured($query)
    {
        return $query->whereNotNull('featured_image');
    }

    public function hasFeaturedImage()
    {
        return !empty($this->featured_image);
    }

    public function getSeoTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getSeoDescriptionAttribute($value)
    {
        return $value ?: substr(strip_tags($this->body), 0, 160);
    }
}