@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-8 py-6">
            <h1 class="text-2xl font-bold text-white">Edit {{ ucfirst($type) }}</h1>
            <p class="text-green-100 mt-2">Update your content</p>
        </div>

        <div class="p-8">
            <form action="{{ route('blog.update', [$type, $content->id]) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')
                
                <!-- Content Type Display -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Content Type</label>
                    <div class="p-4 border-2 border-gray-300 rounded-lg bg-gray-50">
                        <div class="flex items-center">
                            <i class="fas {{ $type === 'tutorial' ? 'fa-graduation-cap text-green-500' : ($type === 'article' ? 'fa-newspaper text-blue-500' : 'fa-pen text-purple-500') }} text-xl mr-3"></i>
                            <div>
                                <div class="font-semibold text-gray-800">{{ ucfirst($type) }}</div>
                                <div class="text-sm text-gray-500">Content type cannot be changed when editing</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $content->title) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('title') border-red-500 @enderror"
                           placeholder="Enter a compelling title..."
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Name -->
                <div class="mb-6">
                    <label for="author_name" class="block text-sm font-semibold text-gray-700 mb-2">Author Name</label>
                    <input type="text" 
                           id="author_name" 
                           name="author_name" 
                           value="{{ old('author_name', $content->author_name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('author_name') border-red-500 @enderror"
                           placeholder="Your name"
                           required>
                    @error('author_name')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Body -->
                <div class="mb-6">
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                    <textarea id="body" 
                              name="body" 
                              rows="12"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('body') border-red-500 @enderror"
                              placeholder="Write your content here..."
                              required>{{ old('body', $content->body) }}</textarea>
                    @error('body')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mb-6">
                    <label for="tags" class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
                    <input type="text" 
                           id="tags" 
                           name="tags" 
                           value="{{ old('tags', is_array($content->tags) ? implode(', ', $content->tags) : $content->tags) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="laravel, php, tutorial (separate with commas)">
                    <p class="text-gray-500 text-sm mt-1">Separate tags with commas</p>
                </div>

                <!-- Post-specific fields -->
                @if($type === 'post')
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">Excerpt</label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Brief description of the post...">{{ old('excerpt', $content->excerpt ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="reading_time" class="block text-sm font-semibold text-gray-700 mb-2">Reading Time (minutes)</label>
                        <input type="number" 
                               id="reading_time" 
                               name="reading_time" 
                               value="{{ old('reading_time', $content->reading_time ?? 5) }}"
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                @endif

                <!-- Article-specific fields -->
                @if($type === 'article')
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="seo_title" class="block text-sm font-semibold text-gray-700 mb-2">SEO Title</label>
                        <input type="text" 
                               id="seo_title" 
                               name="seo_title" 
                               value="{{ old('seo_title', $content->seo_title ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="SEO optimized title">
                    </div>
                    <div>
                        <label for="featured_image" class="block text-sm font-semibold text-gray-700 mb-2">Featured Image URL</label>
                        <input type="url" 
                               id="featured_image" 
                               name="featured_image" 
                               value="{{ old('featured_image', $content->featured_image ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="https://example.com/image.jpg">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="seo_description" class="block text-sm font-semibold text-gray-700 mb-2">SEO Description</label>
                    <textarea id="seo_description" 
                              name="seo_description" 
                              rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="SEO description for search engines...">{{ old('seo_description', $content->seo_description ?? '') }}</textarea>
                </div>
                @endif

                <!-- Tutorial-specific fields -->
                @if($type === 'tutorial')
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="difficulty_level" class="block text-sm font-semibold text-gray-700 mb-2">Difficulty Level</label>
                        <select id="difficulty_level" 
                                name="difficulty_level" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="beginner" {{ (old('difficulty_level', $content->difficulty_level ?? 'beginner') === 'beginner') ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ (old('difficulty_level', $content->difficulty_level ?? 'beginner') === 'intermediate') ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ (old('difficulty_level', $content->difficulty_level ?? 'beginner') === 'advanced') ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label for="estimated_duration" class="block text-sm font-semibold text-gray-700 mb-2">Estimated Duration (minutes)</label>
                        <input type="number" 
                               id="estimated_duration" 
                               name="estimated_duration" 
                               value="{{ old('estimated_duration', $content->estimated_duration ?? 30) }}"
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="steps" class="block text-sm font-semibold text-gray-700 mb-2">Tutorial Steps (JSON format)</label>
                    <textarea id="steps" 
                              name="steps" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono text-sm"
                              placeholder='[{"title": "Step 1", "description": "First step description"}, {"title": "Step 2", "description": "Second step description"}]'>{{ old('steps', is_array($content->steps ?? []) ? json_encode($content->steps, JSON_PRETTY_PRINT) : ($content->steps ?? '')) }}</textarea>
                    <p class="text-gray-500 text-sm mt-1">Enter steps in JSON format</p>
                </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <a href="{{ route('blog.show', [$type, $content->id]) }}" 
                           class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <form action="{{ route('blog.destroy', [$type, $content->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this {{ $type }}? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-6 py-3 bg-green-900 text-white rounded-lg hover:bg-black transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update {{ ucfirst($type) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection