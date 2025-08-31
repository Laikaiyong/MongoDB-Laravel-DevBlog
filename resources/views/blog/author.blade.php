@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    @php
        $authorName = $content->isNotEmpty() ? $content->first()->author_name : 'Author';
    @endphp
    
    <header class="text-center py-8 mb-8">
        <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center">
            <span class="text-white text-2xl font-bold">{{ substr($authorName, 0, 1) }}</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $authorName }}</h1>
        <p class="text-gray-600">Content by {{ $authorName }}</p>
        <nav class="mt-4">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Blog</a>
        </nav>
    </header>

    @if($content->count() > 0)
        <div class="mb-6">
            <p class="text-gray-600">{{ $content->count() }} item{{ $content->count() > 1 ? 's' : '' }} by {{ $authorName }}</p>
        </div>
        
        <!-- Content Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8 max-w-md mx-auto">
            @php
                $posts = $content->where('type', 'post')->count();
                $articles = $content->where('type', 'article')->count();
                $tutorials = $content->where('type', 'tutorial')->count();
            @endphp
            
            <div class="text-center bg-purple-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $posts }}</div>
                <div class="text-sm text-gray-600">Posts</div>
            </div>
            <div class="text-center bg-blue-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $articles }}</div>
                <div class="text-sm text-gray-600">Articles</div>
            </div>
            <div class="text-center bg-green-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $tutorials }}</div>
                <div class="text-sm text-gray-600">Tutorials</div>
            </div>
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
                        <span>{{ $item->published_at->format('M d, Y') }}</span>
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
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Content Found</h3>
                    <p class="text-gray-500 mb-6">{{ $authorName }} hasn't published any content yet. Check back later or explore other authors' work.</p>
                    <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                        Explore All Content
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($content->count() > 0)
        <div class="mt-12 text-center">
            <p class="text-gray-600">All {{ $content->count() }} items by {{ $authorName }}</p>
        </div>
    @endif
</div>
@endsection