<?php

namespace App\Models;

class ContentFactory
{
    public static function create(string $type, array $data): Content
    {
        return match ($type) {
            'post' => Post::create($data),
            'article' => Article::create($data),
            'tutorial' => Tutorial::create($data),
            default => throw new \InvalidArgumentException("Unknown content type: {$type}")
        };
    }

    public static function getByType(string $type)
    {
        return match ($type) {
            'post' => Post::where('type', 'post'),
            'article' => Article::where('type', 'article'),
            'tutorial' => Tutorial::where('type', 'tutorial'),
            default => throw new \InvalidArgumentException("Unknown content type: {$type}")
        };
    }

    public static function getAllContent()
    {
        $posts = Post::all();
        $articles = Article::all();
        $tutorials = Tutorial::all();

        return collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('created_at');
    }

    public static function getPublishedContent()
    {
        $posts = Post::published()->get();
        $articles = Article::published()->get();
        $tutorials = Tutorial::published()->get();

        return collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('published_at');
    }

    public static function getContentByAuthor(string $authorId)
    {
        $posts = Post::byAuthor($authorId)->get();
        $articles = Article::byAuthor($authorId)->get();
        $tutorials = Tutorial::byAuthor($authorId)->get();

        return collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('created_at');
    }
}