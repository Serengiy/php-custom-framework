<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\PostController;
use App\Controllers\RegisterController;
use Somecode\Framework\Http\Middleware\Authenticate;
use Somecode\Framework\Http\Middleware\Guest;
use Somecode\Framework\Http\Response;
use Somecode\Framework\Routing\Route;

return [
    Route::get('/', [PostController::class, 'index']),
    Route::get('/dashboard', [HomeController::class, 'dashboard'], [Authenticate::class]),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::get('/posts/{id}', [PostController::class, 'show']),
    Route::post('/posts', [PostController::class, 'store']),
    Route::get('/register', [RegisterController::class, 'form'], [Guest::class]),
    Route::post('/register', [RegisterController::class, 'register']),
    Route::get('/login', [LoginController::class, 'form'], [Guest::class]),
    Route::post('/login', [LoginController::class, 'login']),
    Route::post('/logout', [LoginController::class, 'logout']),
];
