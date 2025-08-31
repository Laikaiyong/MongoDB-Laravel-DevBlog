@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Navigation -->
    <nav class="mb-8">
        <a href="{{ url('/') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-800 font-medium transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Blog
        </a>
    </nav>

    <!-- Article Container -->
    <article class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Featured Image or Content Type Header -->
        @if($content instanceof App\Models\Article && $content->hasFeaturedImage())
            <div class="relative h-64 md:h-96 overflow-hidden">
                <img src="{{ $content->featured_image }}" 
                     alt="{{ $content->title }}" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6">
                    <span class="inline-flex items-center px-4 py-2 bg-white/90 backdrop-blur-sm text-gray-800 rounded-full font-semibold">
                        <i class="fas fa-newspaper mr-2"></i>
                        {{ ucfirst($content->getContentType()) }}
                    </span>
                </div>
            </div>
        @else
            <div class="h-48 bg-gradient-to-br {{ $content->getContentType() === 'tutorial' ? 'from-green-400 via-green-500 to-green-600' : ($content->getContentType() === 'article' ? 'from-blue-400 via-blue-500 to-blue-600' : 'from-purple-400 via-purple-500 to-purple-600') }} flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative text-center text-white">
                    <i class="fas {{ $content->getContentType() === 'tutorial' ? 'fa-graduation-cap' : ($content->getContentType() === 'article' ? 'fa-newspaper' : 'fa-pen') }} text-5xl mb-4 opacity-90"></i>
                    <span class="text-xl font-bold">{{ ucfirst($content->getContentType()) }}</span>
                </div>
                <!-- Decorative elements -->
                <div class="absolute top-4 right-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 bg-white/10 rounded-full"></div>
            </div>
        @endif
        
        <!-- Content -->
        <div class="p-8 lg:p-12">
            <!-- Header -->
            <header class="mb-10">
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 text-sm font-semibold {{ $content->getContentType() === 'tutorial' ? 'bg-green-100 text-green-700 border border-green-200' : ($content->getContentType() === 'article' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-green-200 text-green-800 border border-green-300') }} rounded-full">
                        <i class="fas {{ $content->getContentType() === 'tutorial' ? 'fa-graduation-cap' : ($content->getContentType() === 'article' ? 'fa-newspaper' : 'fa-pen') }} mr-2"></i>
                        {{ ucfirst($content->getContentType()) }}
                    </span>
                    @if($content instanceof App\Models\Tutorial)
                        <span class="px-4 py-2 text-sm font-semibold {{ $content->difficulty_level === 'beginner' ? 'bg-green-500' : ($content->difficulty_level === 'intermediate' ? 'bg-green-600' : 'bg-green-700') }} text-white rounded-full">
                            <i class="fas fa-signal mr-2"></i>
                            {{ ucfirst($content->difficulty_level) }}
                        </span>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $content->title }}
                </h1>
                
                <!-- Author and Date Info -->
                <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold">{{ substr($content->author_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $content->author_name }}</p>
                            <p class="text-sm">Author</p>
                        </div>
                    </div>
                    <div class="h-12 w-px bg-gray-200 hidden sm:block"></div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $content->published_at->format('M d, Y') }}</p>
                        <p class="text-sm">{{ $content->published_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <!-- Tutorial Info Cards -->
                @if($content instanceof App\Models\Tutorial)
                    <div class="grid sm:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-clock text-green-600 mr-2"></i>
                                <h3 class="font-semibold text-green-800">Duration</h3>
                            </div>
                            <p class="text-green-700 font-medium">{{ $content->getFormattedDuration() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-list text-blue-600 mr-2"></i>
                                <h3 class="font-semibold text-blue-800">Steps</h3>
                            </div>
                            <p class="text-blue-700 font-medium">{{ $content->getStepCount() }} steps</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-signal text-purple-600 mr-2"></i>
                                <h3 class="font-semibold text-purple-800">Level</h3>
                            </div>
                            <p class="text-purple-700 font-medium">{{ ucfirst($content->difficulty_level) }}</p>
                        </div>
                    </div>
                @elseif($content instanceof App\Models\Post && $content->reading_time)
                    <div class="inline-flex items-center px-4 py-2 bg-gray-50 rounded-full border border-green-200 mb-8">
                        <i class="fas fa-book-open text-gray-600 mr-2"></i>
                        <span class="text-gray-700 font-medium">{{ $content->reading_time }} min read</span>
                    </div>
                @endif
                
                <!-- Prerequisites and Learning Objectives for Tutorials -->
                @if($content instanceof App\Models\Tutorial)
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        @if($content->prerequisites)
                            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                                <h3 class="flex items-center font-bold text-yellow-800 mb-4">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Prerequisites
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($content->prerequisites as $prerequisite)
                                        <li class="flex items-start">
                                            <i class="fas fa-check text-yellow-600 mr-3 mt-1 text-sm"></i>
                                            <span class="text-yellow-700">{{ $prerequisite }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if($content->learning_objectives)
                            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                                <h3 class="flex items-center font-bold text-green-800 mb-4">
                                    <i class="fas fa-bullseye mr-2"></i>
                                    What You'll Learn
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($content->learning_objectives as $objective)
                                        <li class="flex items-start">
                                            <i class="fas fa-star text-green-600 mr-3 mt-1 text-sm"></i>
                                            <span class="text-green-700">{{ $objective }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Tags -->
                @if($content->tags)
                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach($content->tags as $tag)
                            <a href="{{ route('blog.tag', $tag) }}" 
                               class="px-3 py-1 text-sm bg-green-50 text-green-700 rounded-full hover:bg-green-100 transition-colors duration-200 border border-green-200">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </header>
            
            <!-- Content Body -->
            <div class="prose prose-lg prose-gray max-w-none mb-12">
                @if($content instanceof App\Models\Tutorial && $content->steps)
                    <div class="space-y-8">
                        @foreach($content->steps as $index => $step)
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-green-200 shadow-sm">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $step['title'] ?? 'Step ' . ($index + 1) }}
                                    </h3>
                                </div>
                                <div class="pl-14">
                                    <div class="text-gray-700 leading-relaxed mb-4">
                                        {!! nl2br(e($step['content'] ?? $step)) !!}
                                    </div>
                                    @if(isset($step['code']))
                                        <div class="relative">
                                            <div class="absolute top-3 right-3 text-xs text-gray-400 font-mono">CODE</div>
                                            <pre class="bg-gray-900 text-gray-100 p-6 rounded-lg overflow-x-auto border border-gray-700"><code class="text-sm">{{ $step['code'] }}</code></pre>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-gray-700 leading-relaxed text-lg">
                        {!! nl2br(e($content->body)) !!}
                    </div>
                @endif
            </div>
            
            <!-- Footer -->
            <footer class="border-t border-green-200 pt-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-2"></i>
                        Published on {{ $content->published_at->format('F d, Y') }}
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('blog.edit', [$content->getContentType(), $content->id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <form action="{{ route('blog.destroy', [$content->getContentType(), $content->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this {{ $content->getContentType() }}? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-900 text-white rounded-lg hover:bg-black transition-colors duration-200 text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('blog.author', $content->author_id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-user mr-2"></i>
                            More by {{ $content->author_name }}
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </article>

    <!-- Related Content Section -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">
            More {{ ucfirst($content->getContentType()) }}s
        </h2>
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-12 text-center border border-green-200">
            <i class="fas fa-compass text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Related Content Coming Soon!</h3>
            <p class="text-gray-600 mb-6">We're working on showing you more relevant content based on this {{ $content->getContentType() }}.</p>
            <a href="{{ url('/') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Browse All Content
            </a>
        </div>
    </div>
</div>
@endsection