<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Article;
use App\Models\Tutorial;

class BlogContentSeeder extends Seeder
{
    public function run()
    {
        // Create Posts
        Post::create([
            'title' => 'Getting Started with MongoDB in Laravel',
            'body' => 'MongoDB is a powerful NoSQL database that integrates beautifully with Laravel. In this post, we\'ll explore the basics of setting up MongoDB with Laravel and why it\'s a great choice for modern web applications.',
            'author_id' => 1,
            'author_name' => 'John Doe',
            'published_at' => now()->subDays(5),
            'tags' => ['mongodb', 'laravel', 'nosql', 'database'],
            'status' => 'published',
            'excerpt' => 'Learn how to integrate MongoDB with Laravel for powerful NoSQL database operations.',
            'reading_time' => 3
        ]);

        Post::create([
            'title' => 'Why We Chose MongoDB for Our Dev Blog',
            'body' => 'After evaluating various database solutions, we decided on MongoDB for our development blog. Here\'s why: flexibility, scalability, and excellent Laravel integration through the MongoDB Laravel package.',
            'author_id' => 2,
            'author_name' => 'Jane Smith',
            'published_at' => now()->subDays(3),
            'tags' => ['mongodb', 'architecture', 'database-design'],
            'status' => 'published',
            'excerpt' => 'Our decision-making process for choosing MongoDB as our blog database.',
            'reading_time' => 2
        ]);

        // Create Articles
        Article::create([
            'title' => 'Advanced MongoDB Aggregation Pipelines in Laravel',
            'body' => 'MongoDB\'s aggregation framework is incredibly powerful for data analysis and reporting. This comprehensive guide covers advanced aggregation techniques, optimization strategies, and real-world examples using Laravel\'s MongoDB integration.',
            'author_id' => 1,
            'author_name' => 'John Doe',
            'published_at' => now()->subDays(7),
            'tags' => ['mongodb', 'aggregation', 'laravel', 'data-analysis'],
            'status' => 'published',
            'category' => 'database',
            'featured_image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800',
            'seo_title' => 'Master MongoDB Aggregation Pipelines in Laravel',
            'seo_description' => 'Learn advanced MongoDB aggregation techniques with practical Laravel examples and performance optimization tips.'
        ]);

        Article::create([
            'title' => 'Building Scalable APIs with Laravel and MongoDB',
            'body' => 'Creating RESTful APIs that can handle millions of requests requires careful planning and the right technology stack. This article explores how Laravel and MongoDB work together to create highly scalable API solutions.',
            'author_id' => 2,
            'author_name' => 'Jane Smith',
            'published_at' => now()->subDays(10),
            'tags' => ['api', 'laravel', 'mongodb', 'scalability', 'rest'],
            'status' => 'published',
            'category' => 'architecture',
            'featured_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
            'seo_title' => 'Scalable Laravel MongoDB APIs',
            'seo_description' => 'Build high-performance, scalable REST APIs using Laravel and MongoDB with best practices and optimization techniques.'
        ]);

        // Create Tutorials
        Tutorial::create([
            'title' => 'Setting Up MongoDB with Laravel: Complete Guide',
            'body' => 'This tutorial will walk you through every step of integrating MongoDB with Laravel, from installation to creating your first models and performing database operations.',
            'author_id' => 1,
            'author_name' => 'John Doe',
            'published_at' => now()->subDays(2),
            'tags' => ['mongodb', 'laravel', 'setup', 'beginner'],
            'status' => 'published',
            'difficulty_level' => 'beginner',
            'estimated_duration' => 45,
            'prerequisites' => [
                'Basic PHP knowledge',
                'Laravel fundamentals',
                'Command line familiarity'
            ],
            'learning_objectives' => [
                'Install and configure MongoDB',
                'Set up Laravel MongoDB package',
                'Create MongoDB models',
                'Perform basic database operations'
            ],
            'steps' => [
                [
                    'title' => 'Install MongoDB',
                    'content' => 'First, we need to install MongoDB on our system. Visit the official MongoDB website and download the appropriate version for your operating system.',
                    'code' => '# On macOS with Homebrew
brew install mongodb-community

# On Ubuntu
sudo apt-get install mongodb'
                ],
                [
                    'title' => 'Install Laravel MongoDB Package',
                    'content' => 'Add the MongoDB Laravel package to your project using Composer.',
                    'code' => 'composer require mongodb/laravel-mongodb'
                ],
                [
                    'title' => 'Configure Database Connection',
                    'content' => 'Update your .env file with MongoDB connection details.',
                    'code' => 'DB_CONNECTION=mongodb
DB_URI=mongodb://127.0.0.1:27017
DB_DATABASE=your_database_name'
                ],
                [
                    'title' => 'Create Your First Model',
                    'content' => 'Create a model that extends MongoDB\\Laravel\\Eloquent\\Model.',
                    'code' => '<?php

namespace App\\Models;

use MongoDB\\Laravel\\Eloquent\\Model;

class User extends Model
{
    protected $connection = \'mongodb\';
    protected $collection = \'users\';
    
    protected $fillable = [\'name\', \'email\'];
}'
                ]
            ]
        ]);

        Tutorial::create([
            'title' => 'Building a Blog with Laravel and MongoDB',
            'body' => 'Learn to build a full-featured blog application using Laravel and MongoDB. This tutorial covers everything from data modeling to implementing search functionality.',
            'author_id' => 2,
            'author_name' => 'Jane Smith',
            'published_at' => now()->subDays(1),
            'tags' => ['mongodb', 'laravel', 'blog', 'project', 'intermediate'],
            'status' => 'published',
            'difficulty_level' => 'intermediate',
            'estimated_duration' => 120,
            'prerequisites' => [
                'Laravel experience',
                'MongoDB basics',
                'HTML/CSS knowledge',
                'JavaScript fundamentals'
            ],
            'learning_objectives' => [
                'Design MongoDB schema for blog',
                'Implement blog CRUD operations',
                'Add search functionality',
                'Create responsive blog views',
                'Handle file uploads for images'
            ],
            'steps' => [
                [
                    'title' => 'Design the Blog Schema',
                    'content' => 'Plan your blog data structure with MongoDB\'s flexible document model.',
                    'code' => '// Example blog post structure
{
    "_id": ObjectId,
    "title": "Post Title",
    "content": "Post content...",
    "author": {
        "id": 1,
        "name": "Author Name"
    },
    "tags": ["tag1", "tag2"],
    "published_at": ISODate,
    "status": "published"
}'
                ],
                [
                    'title' => 'Create Blog Models',
                    'content' => 'Set up Eloquent models for your blog entities.',
                    'code' => 'php artisan make:model Post
php artisan make:model Category
php artisan make:model Comment'
                ],
                [
                    'title' => 'Implement Controllers',
                    'content' => 'Create controllers to handle blog operations.',
                    'code' => 'php artisan make:controller BlogController
php artisan make:controller PostController --resource'
                ]
            ]
        ]);
    }
}