@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <header class="text-center py-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Articles</h1>
        <p class="text-gray-600">In-depth technical articles and comprehensive guides</p>
        <nav class="mt-4">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">← Back to All Content</a>
        </nav>
    </header>

    <div class="grid lg:grid-cols-2 gap-8">
        @forelse($articles as $article)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                @if($article->hasFeaturedImage())
                    <div class="h-48 overflow-hidden">
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                            Article
                        </span>
                        @if($article->category)
                            <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                                {{ ucfirst($article->category) }}
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-3 text-gray-800">
                        <a href="{{ route('blog.show', ['article', $article->id]) }}" class="hover:text-blue-600 transition duration-200">
                            {{ $article->title }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 mb-4">{{ $article->getSeoDescriptionAttribute(null) }}</p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>By {{ $article->author_name }}</span>
                        <span>{{ $article->published_at->format('M d, Y') }}</span>
                    </div>
                    
                    @if($article->tags)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($article->tags, 0, 4) as $tag)
                                <a href="{{ route('blog.tag', $tag) }}" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                            @if(count($article->tags) > 4)
                                <span class="px-2 py-1 text-xs text-gray-500">+{{ count($article->tags) - 4 }} more</span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('blog.show', ['article', $article->id]) }}" 
                       class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                        Read Article
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Articles Yet</h3>
                    <p class="text-gray-500 mb-6">We haven't published any articles yet. Check back soon for in-depth technical content!</p>
                    <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                        View All Content
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($articles->count() > 0)
        <div class="text-center mt-12">
            <p class="text-gray-600">{{ $articles->count() }} articles</p>
        </div>
    @endif
</div>
@endsection