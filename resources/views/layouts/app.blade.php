<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dev Blog - MongoDB & Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Dev Blog</span>
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ url('/') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            All Content
                        </a>
                        <a href="{{ route('blog.posts') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            Posts
                        </a>
                        <a href="{{ route('blog.articles') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            Articles
                        </a>
                        <a href="{{ route('blog.tutorials') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            Tutorials
                        </a>
                    </div>
                </div>
                
                <!-- Search -->
                <div class="flex items-center">
                    <form action="{{ route('blog.search') }}" method="GET" class="hidden md:block">
                        <div class="relative">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Search..." 
                                   value="{{ request('q') }}"
                                   class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Mobile menu button -->
                    <button class="md:hidden ml-4 p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100" 
                            onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 rounded-md">
                    All Content
                </a>
                <a href="{{ route('blog.posts') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 rounded-md">
                    Posts
                </a>
                <a href="{{ route('blog.articles') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 rounded-md">
                    Articles
                </a>
                <a href="{{ route('blog.tutorials') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 rounded-md">
                    Tutorials
                </a>
                <div class="px-3 py-2">
                    <form action="{{ route('blog.search') }}" method="GET">
                        <input type="text" 
                               name="q" 
                               placeholder="Search..." 
                               value="{{ request('q') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">Dev Blog</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        A modern development blog built with Laravel and MongoDB, showcasing polymorphic content models and flexible document storage.
                    </p>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Technologies Used</h6>
                    <ul class="text-gray-400 text-sm space-y-1">
                        <li><i class="fas fa-database mr-2"></i>MongoDB with Laravel</li>
                        <li><i class="fas fa-layer-group mr-2"></i>Polymorphic Models</li>
                        <li><i class="fab fa-php mr-2"></i>Laravel Framework</li>
                        <li><i class="fab fa-css3-alt mr-2"></i>Tailwind CSS</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Dev Blog. Built with Laravel & MongoDB.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>