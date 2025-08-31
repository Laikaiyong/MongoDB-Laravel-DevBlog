# Laravel MongoDB Blog - MVC Architecture & Polymorphism

A modern development blog showcasing **MVC architecture** and **polymorphism** patterns using **Laravel** and **MongoDB**.

## 🏗️ Architecture Overview

This project demonstrates clean separation of concerns through the **Model-View-Controller (MVC)** pattern, enhanced with **polymorphic relationships** to handle different content types in a flexible MongoDB document structure.

---

## 📊 MVC Implementation

### **Model Layer** (`app/Models/`)

The model layer implements a **polymorphic inheritance pattern** using MongoDB's flexible document structure.

#### **Base Content Model** - `Content.php`
```php
abstract class Content extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'contents';
    
    protected $fillable = [
        'title', 'body', 'author_id', 'author_name', 
        'published_at', 'tags', 'status', 'type'
    ];

    // Polymorphic behavior
    abstract public function getContentType();
    
    // Shared behavior
    public function scopePublished($query) {
        return $query->where('status', 'published');
    }
    
    public function isPublished() {
        return $this->status === 'published';
    }
}
```

#### **Concrete Models**

**Post Model** - `Post.php`
```php
class Post extends Content
{
    protected $attributes = ['type' => 'post'];
    
    protected $fillable = [
        'excerpt', 'reading_time', // Post-specific fields
        // ... inherited fields
    ];
    
    public function getContentType() {
        return 'post';
    }
    
    // Post-specific behavior
    public function getExcerptAttribute($value) {
        return $value ?: substr(strip_tags($this->body), 0, 150) . '...';
    }
}
```

**Article Model** - `Article.php`
```php
class Article extends Content
{
    protected $attributes = ['type' => 'article'];
    
    protected $fillable = [
        'category', 'featured_image', 'seo_title', 'seo_description',
        // ... inherited fields
    ];
    
    public function getContentType() {
        return 'article';
    }
    
    // Article-specific behavior
    public function hasFeaturedImage() {
        return !empty($this->featured_image);
    }
}
```

**Tutorial Model** - `Tutorial.php`
```php
class Tutorial extends Content
{
    protected $attributes = ['type' => 'tutorial'];
    
    protected $fillable = [
        'difficulty_level', 'estimated_duration', 'prerequisites', 
        'learning_objectives', 'steps',
        // ... inherited fields
    ];
    
    protected $casts = [
        'prerequisites' => 'array',
        'learning_objectives' => 'array',
        'steps' => 'array',
    ];
    
    public function getContentType() {
        return 'tutorial';
    }
    
    // Tutorial-specific behavior
    public function getFormattedDuration() {
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }
    
    public function getStepCount() {
        return count($this->steps ?? []);
    }
}
```

### **View Layer** (`resources/views/`)

The view layer uses **Blade templating** with a hierarchical structure:

```
views/
├── layouts/
│   └── app.blade.php          # Base layout with Tailwind CSS
└── blog/
    ├── index.blade.php        # Homepage showing all content types
    ├── show.blade.php         # Individual content display
    ├── posts.blade.php        # Posts listing
    ├── articles.blade.php     # Articles listing
    ├── tutorials.blade.php    # Tutorials listing
    ├── search.blade.php       # Search results
    ├── tag.blade.php          # Content by tag
    └── author.blade.php       # Content by author
```

**Polymorphic View Rendering Example**:
```blade
{{-- Dynamic content type handling in views --}}
@foreach($content as $item)
    <div class="content-card {{ $item->getContentType() }}">
        {{-- Polymorphic badge rendering --}}
        <span class="badge {{ $item->getContentType() === 'tutorial' ? 'bg-green' : 
                              ($item->getContentType() === 'article' ? 'bg-blue' : 'bg-purple') }}">
            {{ ucfirst($item->getContentType()) }}
        </span>
        
        {{-- Type-specific content --}}
        @if($item instanceof App\Models\Tutorial)
            <div class="tutorial-meta">
                Duration: {{ $item->getFormattedDuration() }}
                Steps: {{ $item->getStepCount() }}
            </div>
        @elseif($item instanceof App\Models\Post && $item->reading_time)
            <div class="post-meta">
                Reading time: {{ $item->reading_time }} min
            </div>
        @endif
        
        {{-- Universal link generation --}}
        <a href="{{ route('blog.show', [$item->getContentType(), $item->id]) }}">
            {{ $item->title }}
        </a>
    </div>
@endforeach
```

### **Controller Layer** (`app/Http/Controllers/`)

The controller orchestrates the interaction between models and views:

```php
class BlogController extends Controller
{
    public function index()
    {
        // Polymorphic content retrieval
        $content = ContentFactory::getPublishedContent();
        return view('blog.index', compact('content'));
    }

    public function show($type, $id)
    {
        // Dynamic model resolution
        $content = match ($type) {
            'post' => Post::findOrFail($id),
            'article' => Article::findOrFail($id),
            'tutorial' => Tutorial::findOrFail($id),
            default => abort(404)
        };

        if (!$content->isPublished()) {
            abort(404);
        }

        return view('blog.show', compact('content'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        // Search across all content types
        $posts = Post::published()->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('body', 'like', "%{$query}%");
        })->get();

        $articles = Article::published()->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('body', 'like', "%{$query}%");
        })->get();

        $tutorials = Tutorial::published()->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('body', 'like', "%{$query}%");
        })->get();

        // Merge and sort polymorphic collection
        $content = collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('published_at');

        return view('blog.search', compact('content', 'query'));
    }
}
```

---

## 🎭 Polymorphism Implementation

This project showcases **three types of polymorphism**:

### **1. Inheritance Polymorphism**

All content types inherit from the base `Content` class but implement type-specific behavior:

```php
// Base behavior (same interface, different implementations)
$post->getContentType();      // Returns 'post'
$article->getContentType();   // Returns 'article'
$tutorial->getContentType();  // Returns 'tutorial'

// Polymorphic method calls
foreach ($content as $item) {
    echo $item->getContentType(); // Calls appropriate implementation
    echo $item->isPublished();    // Shared behavior
}
```

### **2. Database Polymorphism (Single Table Inheritance)**

All content types are stored in the same MongoDB collection but with different document structures:

```javascript
// MongoDB Documents - Same collection, different schemas

// Post document
{
  "_id": ObjectId("..."),
  "type": "post",
  "title": "Getting Started with MongoDB",
  "body": "MongoDB is a powerful NoSQL database...",
  "excerpt": "Learn MongoDB basics...",
  "reading_time": 5,
  "author_name": "John Doe",
  "status": "published"
}

// Article document  
{
  "_id": ObjectId("..."),
  "type": "article",
  "title": "Advanced MongoDB Aggregation",
  "body": "Aggregation pipelines are...",
  "category": "database",
  "featured_image": "https://...",
  "seo_title": "Master MongoDB Aggregation",
  "author_name": "Jane Smith",
  "status": "published"
}

// Tutorial document
{
  "_id": ObjectId("..."),
  "type": "tutorial", 
  "title": "Building APIs with Laravel & MongoDB",
  "body": "This tutorial covers...",
  "difficulty_level": "intermediate",
  "estimated_duration": 120,
  "prerequisites": ["Laravel basics", "MongoDB knowledge"],
  "learning_objectives": ["Build REST APIs", "Implement authentication"],
  "steps": [
    {
      "title": "Setup Laravel",
      "content": "First, install Laravel...",
      "code": "composer create-project laravel/laravel"
    }
  ],
  "author_name": "John Doe",
  "status": "published"
}
```

### **3. Factory Pattern for Polymorphic Object Creation**

The `ContentFactory` handles creation and retrieval of different content types:

```php
class ContentFactory
{
    public static function create(string $type, array $data): Content
    {
        return match ($type) {
            'post' => Post::create($data),
            'article' => Article::create($data),
            'tutorial' => Tutorial::create($data),
            default => throw new \InvalidArgumentException("Unknown content type: {$type}")
        };
    }

    public static function getPublishedContent()
    {
        $posts = Post::published()->get();
        $articles = Article::published()->get();
        $tutorials = Tutorial::published()->get();

        return collect()
            ->merge($posts)
            ->merge($articles)
            ->merge($tutorials)
            ->sortByDesc('published_at');
    }
}

// Usage
$post = ContentFactory::create('post', $postData);
$article = ContentFactory::create('article', $articleData);
$tutorial = ContentFactory::create('tutorial', $tutorialData);

$allContent = ContentFactory::getPublishedContent();
```

---

## 🚀 Benefits of This Architecture

### **MVC Benefits:**
- **Separation of Concerns**: Models handle data, Views handle presentation, Controllers handle logic
- **Maintainability**: Easy to modify individual components without affecting others
- **Testability**: Each layer can be tested independently
- **Reusability**: Views and models can be reused across different controllers

### **Polymorphism Benefits:**
- **Flexibility**: Easy to add new content types without changing existing code
- **DRY Principle**: Shared behavior is defined once in the base class
- **MongoDB Advantage**: Flexible schema allows different document structures in the same collection
- **Scalability**: New content types can be added without database migrations

### **Code Extensibility Example:**

Adding a new content type (e.g., "Video") requires minimal changes:

```php
// 1. Create new model
class Video extends Content
{
    protected $attributes = ['type' => 'video'];
    
    protected $fillable = [
        'video_url', 'duration', 'thumbnail',
        // ... inherited fields
    ];
    
    public function getContentType() {
        return 'video';
    }
}

// 2. Update factory
public static function create(string $type, array $data): Content
{
    return match ($type) {
        'post' => Post::create($data),
        'article' => Article::create($data), 
        'tutorial' => Tutorial::create($data),
        'video' => Video::create($data), // New type
        default => throw new \InvalidArgumentException("Unknown content type: {$type}")
    };
}

// 3. Update controller route matching
$content = match ($type) {
    'post' => Post::findOrFail($id),
    'article' => Article::findOrFail($id),
    'tutorial' => Tutorial::findOrFail($id),
    'video' => Video::findOrFail($id), // New type
    default => abort(404)
};

// 4. Add view handling for videos in blade templates
@if($item instanceof App\Models\Video)
    <div class="video-meta">
        Duration: {{ $item->duration }}
        <img src="{{ $item->thumbnail }}" alt="Video thumbnail">
    </div>
@endif
```

---

## 🗄️ MongoDB Schema Design

The flexible document structure allows for polymorphic data storage:

```javascript
// Collection: contents
// Documents can have different structures based on 'type' field

db.contents.find().pretty()

// Shared fields (all content types)
{
  "_id": ObjectId,
  "type": String,           // Discriminator field
  "title": String,
  "body": String,
  "author_id": Number,
  "author_name": String,
  "published_at": ISODate,
  "tags": Array,
  "status": String,
  "created_at": ISODate,
  "updated_at": ISODate
}

// Type-specific fields are added based on content type
// Posts: excerpt, reading_time
// Articles: category, featured_image, seo_title, seo_description  
// Tutorials: difficulty_level, estimated_duration, prerequisites, learning_objectives, steps
```

---

## 🔄 Request Flow Example

Here's how a typical request flows through the MVC architecture:

```
1. User visits: /blog/tutorial/64f8a1b2c3d4e5f6g7h8i9j0

2. Route Resolution:
   Route::get('/blog/{type}/{id}', [BlogController::class, 'show'])

3. Controller (BlogController@show):
   - Receives parameters: type='tutorial', id='64f8a1b2c3d4e5f6g7h8i9j0'
   - Uses polymorphic matching to resolve model type
   - Queries MongoDB for Tutorial document
   - Validates content is published
   - Passes $content to view

4. Model (Tutorial):
   - MongoDB Laravel ODM maps document to Tutorial instance
   - Polymorphic methods available: getContentType(), getFormattedDuration()
   - Shared methods from Content: isPublished(), scopePublished()

5. View (blog.show):
   - Receives Tutorial instance
   - Uses instanceof checks for type-specific rendering
   - Renders tutorial-specific UI: steps, prerequisites, learning objectives
   - Generates polymorphic links and metadata

6. Response:
   - Fully rendered HTML with tutorial content
   - Type-specific styling and behavior
   - Navigation and related content links
```

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Database**: MongoDB with Laravel-MongoDB ODM
- **Frontend**: Tailwind CSS, Font Awesome Icons
- **Architecture**: MVC with Polymorphic Models
- **Design Patterns**: Factory, Single Table Inheritance, Strategy

---

## 📁 Project Structure

```
laravel-auth/
├── app/
│   ├── Http/Controllers/
│   │   └── BlogController.php          # Main controller
│   └── Models/
│       ├── Content.php                 # Abstract base model
│       ├── Post.php                    # Post model
│       ├── Article.php                 # Article model
│       ├── Tutorial.php                # Tutorial model
│       └── ContentFactory.php          # Factory for polymorphic creation
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php               # Base layout
│   └── blog/                           # Blog views
├── database/
│   └── seeders/
│       └── BlogContentSeeder.php       # Sample data
├── routes/
│   └── web.php                         # Application routes
└── config/
    └── database.php                    # MongoDB configuration
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- MongoDB
- Node.js & NPM

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-auth
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install MongoDB PHP Extension**
   ```bash
   pecl install mongodb
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update .env with MongoDB settings**
   ```env
   DB_CONNECTION=mongodb
   DB_URI="mongodb://127.0.0.1:27017"
   DB_DATABASE=laravel_dev_blog
   ```

6. **Seed the database**
   ```bash
   php artisan db:seed --class=BlogContentSeeder
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to see your MongoDB-powered blog!

---

This architecture demonstrates how **MVC patterns** and **polymorphism** can create maintainable, extensible applications that leverage MongoDB's flexible document structure while maintaining clean separation of concerns.
