<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginRegisterController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;



Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware(['oauth_exception:api','auth:api'])->group(function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);

    Route::get('/articles/current_user', [ArticleController::class, 'indexCurrentUser']);
    Route::resource('/articles', ArticleController::class);

    Route::get('/articles/{articleId}/comments', [CommentController::class, 'index']);
    Route::get('/articles/{articleId}/comments/{commentId}', [CommentController::class, 'show']);
    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store']);
    Route::put('/articles/{articleId}/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/articles/{articleId}/comments/{commentId}', [CommentController::class, 'destroy']);

    Route::get('/articles/{articleId}/likes', [LikeController::class, 'index']);
    Route::get('/articles/{articleId}/likes/{likeId}', [LikeController::class, 'show']);
    Route::post('/articles/{articleId}/likes', [LikeController::class, 'store']);
    Route::put('/articles/{articleId}/likes/{likeId}', [LikeController::class, 'update']);
    Route::delete('/articles/{articleId}/likes/{likeId}', [LikeController::class, 'destroy']);
});

Route::get('unauthorized', function () {
    return response()->json([
        'status' => 401,
        'message' => "unauthorized. missing token"
    ], 401);
})->name('unauthorized');
