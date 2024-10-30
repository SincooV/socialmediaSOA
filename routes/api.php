<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users/{User}', [UserController::class, 'update']);
    Route::delete('/users/{User}', [UserController::class, 'destroy']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::apiResource('posts.comments', CommentsController::class);
    Route::put('/comment/{id}', [CommentsController::class, 'update']);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/posts', PostController::class);
});