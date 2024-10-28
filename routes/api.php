<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('/users', UserController::class);
Route::apiResource('/posts', PostController::class);
Route::post('/login', [UserController::class, 'login']);
Route::apiResource('/pcomments', CommentController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users/{User}', [UserController::class, 'update']);
    Route::delete('/users/{User}', [UserController::class, 'destroy']);
    Route::post('/posts', [PostController::class, 'store']);

});