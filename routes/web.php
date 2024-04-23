<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('', [AuthController::class,'index'])->name('index');
Route::get('signup',[AuthController::class,'signup'])->name('signup');

Route::post('/login',[AuthController::class,'auth'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard')->middleware('auth');


Route::get('/udashboard',[UserController::class,'show'])->name('user.dashboard')->middleware('auth');;
Route::get('/postblog',[UserController::class,'showblogpg'])->name('user.postblog')->middleware('auth');;
Route::post('/post',[UserController::class,'post_blog'])->name('post');


