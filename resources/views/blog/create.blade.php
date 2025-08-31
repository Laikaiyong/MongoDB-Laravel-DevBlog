@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <h1 class="text-2xl font-bold text-white">Create New Content</h1>
            <p class="text-green-100 mt-2">Share your knowledge with the community</p>
        </div>

        <div class="p-8">
            <form action="{{ route('blog.store') }}" method="POST" id="createForm">
                @csrf
                
                <!-- Content Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Content Type</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="relative">
                            <input type="radio" name="type" value="post" class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300 transition-all">
                                <div class="text-center">
                                    <i class="fas fa-pen text-2xl text-green-500 mb-2"></i>
                                    <div class="font-semibold text-gray-800">Post</div>
                                    <div class="text-xs text-gray-500">Blog post or article</div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative">
                            <input type="radio" name="type" value="article" class="sr-only peer">
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-600 peer-checked:bg-green-50 hover:border-green-400 transition-all">
                                <div class="text-center">
                                    <i class="fas fa-newspaper text-2xl text-green-600 mb-2"></i>
                                    <div class="font-semibold text-gray-800">Article</div>
                                    <div class="text-xs text-gray-500">In-depth article</div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative">
                            <input type="radio" name="type" value="tutorial" class="sr-only peer">
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-700 peer-checked:bg-green-50 hover:border-green-500 transition-all">
                                <div class="text-center">
                                    <i class="fas fa-graduation-cap text-2xl text-green-700 mb-2"></i>
                                    <div class="font-semibold text-gray-800">Tutorial</div>
                                    <div class="text-xs text-gray-500">Step-by-step guide</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
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
                           value="{{ old('author_name') }}"
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
                              required>{{ old('body') }}</textarea>
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
                           value="{{ old('tags') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="laravel, php, tutorial (separate with commas)">
                    <p class="text-gray-500 text-sm mt-1">Separate tags with commas</p>
                </div>

                <!-- Post-specific fields -->
                <div id="post-fields" class="hidden">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">Excerpt</label>
                            <textarea id="excerpt" 
                                      name="excerpt" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Brief description of the post...">{{ old('excerpt') }}</textarea>
                        </div>
                        <div>
                            <label for="reading_time" class="block text-sm font-semibold text-gray-700 mb-2">Reading Time (minutes)</label>
                            <input type="number" 
                                   id="reading_time" 
                                   name="reading_time" 
                                   value="{{ old('reading_time', 5) }}"
                                   min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>

                <!-- Article-specific fields -->
                <div id="article-fields" class="hidden">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="seo_title" class="block text-sm font-semibold text-gray-700 mb-2">SEO Title</label>
                            <input type="text" 
                                   id="seo_title" 
                                   name="seo_title" 
                                   value="{{ old('seo_title') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="SEO optimized title">
                        </div>
                        <div>
                            <label for="featured_image" class="block text-sm font-semibold text-gray-700 mb-2">Featured Image URL</label>
                            <input type="url" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   value="{{ old('featured_image') }}"
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
                                  placeholder="SEO description for search engines...">{{ old('seo_description') }}</textarea>
                    </div>
                </div>

                <!-- Tutorial-specific fields -->
                <div id="tutorial-fields" class="hidden">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="difficulty_level" class="block text-sm font-semibold text-gray-700 mb-2">Difficulty Level</label>
                            <select id="difficulty_level" 
                                    name="difficulty_level" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="estimated_duration" class="block text-sm font-semibold text-gray-700 mb-2">Estimated Duration (minutes)</label>
                            <input type="number" 
                                   id="estimated_duration" 
                                   name="estimated_duration" 
                                   value="{{ old('estimated_duration', 30) }}"
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
                                  placeholder='[{"title": "Step 1", "description": "First step description"}, {"title": "Step 2", "description": "Second step description"}]'>{{ old('steps') }}</textarea>
                        <p class="text-gray-500 text-sm mt-1">Enter steps in JSON format</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('blog.index') }}" 
                       class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        Create Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const postFields = document.getElementById('post-fields');
    const articleFields = document.getElementById('article-fields');
    const tutorialFields = document.getElementById('tutorial-fields');

    function toggleFields() {
        postFields.classList.add('hidden');
        articleFields.classList.add('hidden');
        tutorialFields.classList.add('hidden');

        const selectedType = document.querySelector('input[name="type"]:checked');
        if (selectedType) {
            switch (selectedType.value) {
                case 'post':
                    postFields.classList.remove('hidden');
                    break;
                case 'article':
                    articleFields.classList.remove('hidden');
                    break;
                case 'tutorial':
                    tutorialFields.classList.remove('hidden');
                    break;
            }
        }
    }

    typeRadios.forEach(radio => {
        radio.addEventListener('change', toggleFields);
    });

    // Initialize on page load
    toggleFields();
});
</script>
@endsection