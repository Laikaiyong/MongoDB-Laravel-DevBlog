@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <header class="text-center py-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Tutorials</h1>
        <p class="text-gray-600">Step-by-step guides to help you learn and build</p>
        <nav class="mt-4">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">← Back to All Content</a>
        </nav>
    </header>

    <!-- Difficulty Filter -->
    <div class="flex justify-center mb-8 space-x-2">
        <span class="text-sm text-gray-600 mr-2">Filter by difficulty:</span>
        <a href="{{ route('blog.tutorials') }}" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">All</a>
        <a href="{{ route('blog.tutorials') }}?difficulty=beginner" class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded hover:bg-green-200">Beginner</a>
        <a href="{{ route('blog.tutorials') }}?difficulty=intermediate" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">Intermediate</a>
        <a href="{{ route('blog.tutorials') }}?difficulty=advanced" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded hover:bg-red-200">Advanced</a>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        @forelse($tutorials as $tutorial)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                            Tutorial
                        </span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold {{ $tutorial->getDifficultyBadgeClass() }} text-white rounded-full">
                            {{ ucfirst($tutorial->difficulty_level) }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">{{ $tutorial->getStepCount() }} steps</span>
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-3 text-gray-800">
                        <a href="{{ route('blog.show', ['tutorial', $tutorial->id]) }}" class="hover:text-green-600 transition duration-200">
                            {{ $tutorial->title }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($tutorial->body), 150) }}</p>
                    
                    <!-- Tutorial Meta Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">Duration:</span>
                                <span class="text-gray-600">{{ $tutorial->getFormattedDuration() }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Level:</span>
                                <span class="text-gray-600">{{ ucfirst($tutorial->difficulty_level) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($tutorial->prerequisites)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-1">Prerequisites:</h4>
                            <ul class="text-sm text-gray-600 list-disc list-inside">
                                @foreach(array_slice($tutorial->prerequisites, 0, 2) as $prerequisite)
                                    <li>{{ $prerequisite }}</li>
                                @endforeach
                                @if(count($tutorial->prerequisites) > 2)
                                    <li class="text-gray-500">+{{ count($tutorial->prerequisites) - 2 }} more...</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    
                    @if($tutorial->learning_objectives)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-1">You'll learn:</h4>
                            <ul class="text-sm text-gray-600 list-disc list-inside">
                                @foreach(array_slice($tutorial->learning_objectives, 0, 2) as $objective)
                                    <li>{{ $objective }}</li>
                                @endforeach
                                @if(count($tutorial->learning_objectives) > 2)
                                    <li class="text-gray-500">+{{ count($tutorial->learning_objectives) - 2 }} more...</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>By {{ $tutorial->author_name }}</span>
                        <span>{{ $tutorial->published_at->format('M d, Y') }}</span>
                    </div>
                    
                    @if($tutorial->tags)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($tutorial->tags, 0, 3) as $tag)
                                <a href="{{ route('blog.tag', $tag) }}" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                            @if(count($tutorial->tags) > 3)
                                <span class="px-2 py-1 text-xs text-gray-500">+{{ count($tutorial->tags) - 3 }} more</span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('blog.show', ['tutorial', $tutorial->id]) }}" 
                       class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-200">
                        Start Tutorial
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Tutorials Yet</h3>
                    <p class="text-gray-500 mb-6">We haven't published any tutorials yet. Check back soon for step-by-step guides!</p>
                    <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                        View All Content
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($tutorials->count() > 0)
        <div class="text-center mt-12">
            <p class="text-gray-600">{{ $tutorials->count() }} tutorials</p>
        </div>
    @endif
</div>
@endsection