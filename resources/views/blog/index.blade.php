@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-green-500 to-green-700 rounded-2xl mb-12 mt-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative px-8 py-16 sm:px-16 sm:py-24 text-center">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                    <span class="block">Welcome to</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-green-200 to-green-100">Dev Blog</span>
                </h1>
                <p class="text-xl sm:text-2xl text-green-100 mb-8 max-w-2xl mx-auto">
                    Discover the latest in web development, tutorials, and technical insights powered by MongoDB and Laravel
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <form action="{{ route('blog.search') }}" method="GET" class="w-full sm:w-auto">
                        <div class="relative max-w-md mx-auto">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Search articles, posts, tutorials..." 
                                   class="w-full pl-12 pr-4 py-3 text-gray-900 bg-white/95 backdrop-blur-sm border-0 rounded-xl focus:ring-2 focus:ring-green-400/50 focus:outline-none shadow-lg"
                                   value="{{ request('q') }}">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-16 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-white/10 rounded-full"></div>
    </div>

    <!-- Content Type Filters -->
    <div class="flex flex-wrap justify-center gap-3 mb-12">
        <a href="{{ url('/') }}" 
           class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-medium"
            <i class="fas fa-th-large mr-2"></i>All Content
        </a>
        <a href="{{ route('blog.posts') }}" 
           class="px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-medium">
            <i class="fas fa-pen mr-2"></i>Posts
        </a>
        <a href="{{ route('blog.articles') }}" 
           class="px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-medium">
            <i class="fas fa-newspaper mr-2"></i>Articles
        </a>
        <a href="{{ route('blog.tutorials') }}" 
           class="px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-medium">
            <i class="fas fa-graduation-cap mr-2"></i>Tutorials
        </a>
        <a href="{{ route('blog.create') }}" 
           class="px-6 py-3 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg hover:from-green-600 hover:to-teal-600 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-medium">
            <i class="fas fa-plus mr-2"></i>Create New
        </a>
    </div>

    <!-- Content Grid -->
    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
        @forelse($content as $item)
            <article class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-gray-200">
                <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}" class="block">
                    @if($item instanceof App\Models\Article && $item->hasFeaturedImage())
                        <div class="relative overflow-hidden h-48">
                            <img src="{{ $item->featured_image }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br {{ $item->getContentType() === 'tutorial' ? 'from-green-500 to-green-700' : ($item->getContentType() === 'article' ? 'from-green-400 to-green-600' : 'from-green-300 to-green-500') }} flex items-center justify-center">
                            <i class="fas {{ $item->getContentType() === 'tutorial' ? 'fa-graduation-cap' : ($item->getContentType() === 'article' ? 'fa-newspaper' : 'fa-pen') }} text-4xl text-white/80"></i>
                        </div>
                    @endif
                </a>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-xs font-semibold {{ $item->getContentType() === 'tutorial' ? 'bg-green-100 text-green-800' : ($item->getContentType() === 'article' ? 'bg-green-50 text-green-700' : 'bg-green-200 text-green-800') }} rounded-full">
                            <i class="fas {{ $item->getContentType() === 'tutorial' ? 'fa-graduation-cap' : ($item->getContentType() === 'article' ? 'fa-newspaper' : 'fa-pen') }} mr-1"></i>
                            {{ ucfirst($item->getContentType()) }}
                        </span>
                        @if($item instanceof App\Models\Tutorial)
                            <span class="px-3 py-1 text-xs font-semibold {{ $item->difficulty_level === 'beginner' ? 'bg-green-500' : ($item->difficulty_level === 'intermediate' ? 'bg-green-600' : 'bg-green-700') }} text-white rounded-full">
                                {{ ucfirst($item->difficulty_level) }}
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-green-600 transition-colors duration-200 line-clamp-2">
                        <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}" class="hover:underline">
                            {{ $item->title }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed line-clamp-3">
                        @if($item instanceof App\Models\Post && $item->excerpt)
                            {{ $item->excerpt }}
                        @elseif($item instanceof App\Models\Article)
                            {{ $item->getSeoDescriptionAttribute(null) }}
                        @else
                            {{ Str::limit(strip_tags($item->body), 120) }}
                        @endif
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-xs font-bold text-white">{{ substr($item->author_name, 0, 1) }}</span>
                            </div>
                            <span class="font-medium">{{ $item->author_name }}</span>
                        </div>
                        <span class="text-xs">{{ $item->published_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($item instanceof App\Models\Tutorial)
                        <div class="flex items-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-lg p-2">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            <span>{{ $item->getFormattedDuration() }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-list mr-1 text-gray-400"></i>
                            <span>{{ $item->getStepCount() }} steps</span>
                        </div>
                    @elseif($item instanceof App\Models\Post && $item->reading_time)
                        <div class="flex items-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-lg p-2">
                            <i class="fas fa-book-open mr-2 text-gray-400"></i>
                            <span>{{ $item->reading_time }} min read</span>
                        </div>
                    @endif
                    
                    @if($item->tags)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($item->tags, 0, 3) as $tag)
                                <a href="{{ route('blog.tag', $tag) }}" 
                                   class="px-2 py-1 text-xs bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors duration-200">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                            @if(count($item->tags) > 3)
                                <span class="px-2 py-1 text-xs text-gray-500">+{{ count($item->tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif
                    
                    <div class="flex gap-2">
                        <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}" 
                           class="inline-flex items-center px-4 py-2 {{ $item->getContentType() === 'tutorial' ? 'bg-green-600 hover:bg-green-700' : ($item->getContentType() === 'article' ? 'bg-green-500 hover:bg-green-600' : 'bg-green-700 hover:bg-green-800') }} text-white rounded-lg font-medium transition-colors duration-200 group-hover:shadow-md flex-1">
                            Read More
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('blog.edit', [$item->getContentType(), $item->id]) }}" 
                           class="inline-flex items-center px-3 py-2 bg-green-800 hover:bg-green-900 text-white rounded-lg font-medium transition-colors duration-200" 
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('blog.destroy', [$item->getContentType(), $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this {{ $item->getContentType() }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-green-900 hover:bg-black text-white rounded-lg font-medium transition-colors duration-200" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Content Yet</h3>
                    <p class="text-gray-500">Check back later for exciting dev blog updates!</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($content->count() > 0)
        <div class="text-center mt-16 mb-8">
            <div class="inline-flex items-center px-6 py-3 bg-white rounded-lg shadow-sm border border-gray-200">
                <i class="fas fa-layer-group text-gray-400 mr-2"></i>
                <span class="text-gray-600 font-medium">Showing {{ $content->count() }} {{ $content->count() === 1 ? 'item' : 'items' }}</span>
            </div>
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection