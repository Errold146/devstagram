<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::get('/buscar', [UserController::class, 'search'])->name('users.search');

Route::get('/{user:username}', [PostController::class, 'index'])->name('post.index');
Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::post('/{user:username}/posts/{post}', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/{user:username}/posts/{post}/comments/latest', [CommentController::class, 'latest'])->name('comments.latest');

Route::post('/imagenes', [ImageController::class, 'store'])->name('image.store');
Route::post('/perfil/imagen', [ProfileController::class, 'updateImage'])->middleware('auth')->name('profile.image.update');
Route::patch('/perfil', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->middleware('auth')->name('posts.likes.store');
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->middleware('auth')->name('posts.likes.destroy');

Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->middleware('auth')->name('users.follow');
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->middleware('auth')->name('users.unfollow');
