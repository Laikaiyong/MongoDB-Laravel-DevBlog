<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', [BlogController::class, 'index']);

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/posts', [BlogController::class, 'posts'])->name('posts');
    Route::get('/articles', [BlogController::class, 'articles'])->name('articles');
    Route::get('/tutorials', [BlogController::class, 'tutorials'])->name('tutorials');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/author/{authorId}', [BlogController::class, 'byAuthor'])->name('author');
    Route::get('/tag/{tag}', [BlogController::class, 'byTag'])->name('tag');
    
    // CRUD Routes
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::post('/store', [BlogController::class, 'store'])->name('store');
    Route::get('/{type}/{id}/edit', [BlogController::class, 'edit'])->name('edit')
        ->where('type', 'post|article|tutorial');
    Route::put('/{type}/{id}', [BlogController::class, 'update'])->name('update')
        ->where('type', 'post|article|tutorial');
    Route::delete('/{type}/{id}', [BlogController::class, 'destroy'])->name('destroy')
        ->where('type', 'post|article|tutorial');
    
    Route::get('/{type}/{id}', [BlogController::class, 'show'])->name('show')
        ->where('type', 'post|article|tutorial');
});
