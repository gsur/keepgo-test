<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PublishController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Baseline endpoint — Resource контроллер для Posts
Route::apiResource('posts', PostController::class);

Route::prefix('posts/{post}')->group(function () {

    Route::post('tags', [\App\Http\Controllers\TagController::class, 'attach']);
    Route::delete('tags/{tag}', [\App\Http\Controllers\TagController::class, 'detach']);
});

// Блок Б — код-ревью, не рефакторить
Route::post('/publish/batch', [PublishController::class, 'batch']);
Route::get('/publish/report', [PublishController::class, 'report']);
