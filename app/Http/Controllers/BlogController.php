<?php

namespace App\Http\Controllers;

use App\Models\ContentFactory;
use App\Models\Post;
use App\Models\Article;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $content = ContentFactory::getPublishedContent();
        
        return view('blog.index', compact('content'));
    }

    public function show($type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        if (!$content->isPublished()) {
            abort(404);
        }

        return view('blog.show', compact('content'));
    }

    public function posts()
    {
        $posts = Post::published()->orderBy('published_at', 'desc')->get();
        return view('blog.posts', compact('posts'));
    }

    public function articles()
    {
        $articles = Article::published()->orderBy('published_at', 'desc')->get();
        return view('blog.articles', compact('articles'));
    }

    public function tutorials()
    {
        $tutorials = Tutorial::published()->orderBy('published_at', 'desc')->get();
        return view('blog.tutorials', compact('tutorials'));
    }

    public function byAuthor($authorId)
    {
        $content = ContentFactory::getContentByAuthor($authorId);
        $content = $content->filter(fn($item) => $item->isPublished());
        
        return view('blog.author', compact('content', 'authorId'));
    }

    public function byTag($tag)
    {
        $posts = Post::published()->where('tags', $tag)->get();
        $articles = Article::published()->where('tags', $tag)->get();
        $tutorials = Tutorial::published()->where('tags', $tag)->get();

        $content = collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('published_at');

        return view('blog.tag', compact('content', 'tag'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('blog.index');
        }

        $posts = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%");
            })->get();

        $articles = Article::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%");
            })->get();

        $tutorials = Tutorial::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%");
            })->get();

        $content = collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('published_at');

        return view('blog.search', compact('content', 'query'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,article,tutorial',
            'title' => 'required|max:255',
            'body' => 'required',
            'author_name' => 'required|max:255',
        ]);

        $modelClass = match ($request->type) {
            'post' => Post::class,
            'article' => Article::class,
            'tutorial' => Tutorial::class,
        };

        $data = $request->only(['title', 'body', 'author_name']);
        $data['published_at'] = now();
        $data['status'] = 'published';

        if ($request->type === 'post') {
            $data['excerpt'] = $request->excerpt;
            $data['reading_time'] = $request->reading_time ?? 5;
        } elseif ($request->type === 'article') {
            $data['featured_image'] = $request->featured_image;
            $data['seo_title'] = $request->seo_title;
            $data['seo_description'] = $request->seo_description;
        } elseif ($request->type === 'tutorial') {
            $data['difficulty_level'] = $request->difficulty_level ?? 'beginner';
            $data['estimated_duration'] = $request->estimated_duration ?? 30;
            $data['steps'] = $request->steps ? json_decode($request->steps, true) : [];
        }

        if ($request->tags) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $content = $modelClass::create($data);

        // For debugging: let's redirect to the index page instead for now
        return redirect()->route('blog.index')
                        ->with('success', ucfirst($request->type) . ' created successfully!');
    }

    public function edit($type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        return view('blog.edit', compact('content', 'type'));
    }

    public function update(Request $request, $type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'author_name' => 'required|max:255',
        ]);

        $data = $request->only(['title', 'body', 'author_name']);

        if ($type === 'post') {
            $data['excerpt'] = $request->excerpt;
            $data['reading_time'] = $request->reading_time ?? 5;
        } elseif ($type === 'article') {
            $data['featured_image'] = $request->featured_image;
            $data['seo_title'] = $request->seo_title;
            $data['seo_description'] = $request->seo_description;
        } elseif ($type === 'tutorial') {
            $data['difficulty_level'] = $request->difficulty_level ?? 'beginner';
            $data['estimated_duration'] = $request->estimated_duration ?? 30;
            if ($request->steps) {
                $data['steps'] = json_decode($request->steps, true);
            }
        }

        if ($request->tags) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $content->update($data);

        // Redirect to index instead of show to avoid ID issues
        return redirect()->route('blog.index')
                        ->with('success', ucfirst($type) . ' updated successfully!');
    }

    public function destroy($type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        $content->delete();

        return redirect()->route('blog.index')
                        ->with('success', ucfirst($type) . ' deleted successfully!');
    }
}