@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <header class="text-center py-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Blog Posts</h1>
        <p class="text-gray-600">Quick thoughts, insights, and updates from our development journey</p>
        <nav class="mt-4">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">← Back to All Content</a>
        </nav>
    </header>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">
                            Post
                        </span>
                        @if($post->reading_time)
                            <span class="ml-2 text-sm text-gray-500">{{ $post->reading_time }} min read</span>
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold mb-3 text-gray-800">
                        <a href="{{ route('blog.show', ['post', $post->id]) }}" class="hover:text-blue-600 transition duration-200">
                            {{ $post->title }}
                        </a>
                    </h2>
                    
                    @if($post->excerpt)
                        <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>By {{ $post->author_name }}</span>
                        <span>{{ $post->published_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($post->tags)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($post->tags, 0, 3) as $tag)
                                <a href="{{ route('blog.tag', $tag) }}" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                            @if(count($post->tags) > 3)
                                <span class="px-2 py-1 text-xs text-gray-500">+{{ count($post->tags) - 3 }} more</span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('blog.show', ['post', $post->id]) }}" 
                       class="inline-block px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition duration-200">
                        Read Post
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Posts Yet</h3>
                    <p class="text-gray-500 mb-6">We haven't published any blog posts yet. Check back soon for the latest updates!</p>
                    <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                        View All Content
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($posts->count() > 0)
        <div class="text-center mt-12">
            <p class="text-gray-600">{{ $posts->count() }} blog posts</p>
        </div>
    @endif
</div>
@endsection