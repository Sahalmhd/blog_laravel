<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Faker\Guesser\Name;

// Authentication Routes
Route::get('/', [AuthController::class, 'index'])->name('index');
Route::get('signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'auth'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/udashboard', [UserController::class, 'show'])->name('user.dashboard');
    Route::get('/postblog', [UserController::class, 'showblogpg'])->name('user.postblog');
    Route::post('/post', [UserController::class, 'post_blog'])->name('post');
    Route::get('/edit/{id}', [UserController::class, 'edit_blog_page'])->name('edit');    
    Route::post('/update/{id}', [UserController::class ,'update_blog_post'])->name('update');
    Route::get('/userpost',[UserController::class,'user_posts'])->name('userpost');
    Route::delete('/posts/{id}',[UserController::class,'destroy'])->name('delete');
});
