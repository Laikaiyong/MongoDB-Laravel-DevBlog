<?php

namespace App\Http\Controllers;

use App\Models\ContentFactory;
use App\Models\Post;
use App\Models\Article;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPosts = Post::count();
        $totalArticles = Article::count();
        $totalTutorials = Tutorial::count();
        $publishedCount = ContentFactory::getPublishedContent()->count();
        
        $recentContent = ContentFactory::getAllContent()->take(10);

        return view('admin.dashboard', compact(
            'totalPosts',
            'totalArticles', 
            'totalTutorials',
            'publishedCount',
            'recentContent'
        ));
    }

    public function createContent(Request $request)
    {
        return view('admin.create');
    }

    public function storeContent(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,article,tutorial',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'author_name' => 'required|string|max:255',
            'tags' => 'array',
            'status' => 'in:draft,published'
        ]);

        $data = $request->all();
        $data['author_id'] = auth()->id() ?? 'system';
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        if ($request->type === 'tutorial') {
            $data['difficulty_level'] = $request->difficulty_level ?? 'beginner';
            $data['estimated_duration'] = $request->estimated_duration ?? 30;
            $data['prerequisites'] = $request->prerequisites ?? [];
            $data['learning_objectives'] = $request->learning_objectives ?? [];
            $data['steps'] = $request->steps ?? [];
        }

        if ($request->type === 'article') {
            $data['category'] = $request->category ?? 'general';
            $data['featured_image'] = $request->featured_image;
            $data['seo_title'] = $request->seo_title;
            $data['seo_description'] = $request->seo_description;
        }

        if ($request->type === 'post') {
            $data['excerpt'] = $request->excerpt;
        }

        $content = ContentFactory::create($request->type, $data);

        return redirect()->route('admin.dashboard')->with('success', 'Content created successfully!');
    }

    public function editContent($type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        return view('admin.edit', compact('content'));
    }

    public function updateContent(Request $request, $type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'author_name' => 'required|string|max:255',
            'tags' => 'array',
            'status' => 'in:draft,published'
        ]);

        $data = $request->all();
        
        if ($request->status === 'published' && !$content->isPublished()) {
            $data['published_at'] = now();
        }

        $content->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Content updated successfully!');
    }

    public function deleteContent($type, $id)
    {
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        $content->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Content deleted successfully!');
    }

    public function polymorphismDemo()
    {
        $contentTypes = [
            'Posts' => Post::all(),
            'Articles' => Article::all(),
            'Tutorials' => Tutorial::all()
        ];

        $mixedContent = ContentFactory::getAllContent();

        return view('admin.polymorphism-demo', compact('contentTypes', 'mixedContent'));
    }
}