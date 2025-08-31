@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <header class="text-center py-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Search Results</h1>
        <p class="text-gray-600">Results for "{{ $query }}"</p>
        <nav class="mt-4">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Blog</a>
        </nav>
    </header>

    <!-- Search Bar -->
    <div class="max-w-md mx-auto mb-8">
        <form action="{{ route('blog.search') }}" method="GET">
            <div class="relative">
                <input type="text" 
                       name="q" 
                       placeholder="Search articles, posts, tutorials..." 
                       class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border rounded-lg focus:outline-none focus:border-blue-500"
                       value="{{ $query }}">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    @if($content->count() > 0)
        <div class="mb-6">
            <p class="text-gray-600">Found {{ $content->count() }} result{{ $content->count() > 1 ? 's' : '' }}</p>
        </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($content as $item)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                @if($item instanceof App\Models\Article && $item->hasFeaturedImage())
                    <img src="{{ $item->featured_image }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                @endif
                
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 text-xs font-semibold {{ $item->getContentType() === 'tutorial' ? 'bg-green-100 text-green-800' : ($item->getContentType() === 'article' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }} rounded-full">
                            {{ ucfirst($item->getContentType()) }}
                        </span>
                        @if($item instanceof App\Models\Tutorial)
                            <span class="ml-2 px-2 py-1 text-xs font-semibold {{ $item->getDifficultyBadgeClass() }} text-white rounded-full">
                                {{ ucfirst($item->difficulty_level) }}
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold mb-2 text-gray-800">
                        <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}" class="hover:text-blue-600 transition duration-200">
                            {{ $item->title }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 mb-4">
                        @if($item instanceof App\Models\Post && $item->excerpt)
                            {{ $item->excerpt }}
                        @elseif($item instanceof App\Models\Article)
                            {{ $item->getSeoDescriptionAttribute(null) }}
                        @else
                            {{ Str::limit(strip_tags($item->body), 150) }}
                        @endif
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>By {{ $item->author_name }}</span>
                        <span>{{ $item->published_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($item instanceof App\Models\Tutorial)
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <span>{{ $item->getFormattedDuration() }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $item->getStepCount() }} steps</span>
                        </div>
                    @elseif($item instanceof App\Models\Post && $item->reading_time)
                        <div class="text-sm text-gray-500 mb-4">
                            {{ $item->reading_time }} min read
                        </div>
                    @endif
                    
                    @if($item->tags)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($item->tags, 0, 3) as $tag)
                                <a href="{{ route('blog.tag', $tag) }}" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                            @if(count($item->tags) > 3)
                                <span class="px-2 py-1 text-xs text-gray-500">+{{ count($item->tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}" 
                       class="inline-block px-4 py-2 {{ $item->getContentType() === 'tutorial' ? 'bg-green-500 hover:bg-green-600' : ($item->getContentType() === 'article' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-purple-500 hover:bg-purple-600') }} text-white rounded transition duration-200">
                        Read More
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Results Found</h3>
                    <p class="text-gray-500 mb-6">We couldn't find any content matching "{{ $query }}". Try a different search term or browse our content categories.</p>
                    <div class="space-x-2">
                        <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                            All Content
                        </a>
                        <a href="{{ route('blog.posts') }}" class="inline-block px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition duration-200">
                            Posts
                        </a>
                        <a href="{{ route('blog.articles') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                            Articles
                        </a>
                        <a href="{{ route('blog.tutorials') }}" class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-200">
                            Tutorials
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection