<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function index($articleId)
    {
        $article = Article::find($articleId);
        if (!$article) {
            return response()->json([
                "status" => 404,
                "message" => "article not found",
            ], 404);
        }
        $likes = $article->likes()->get();
        return response()->json([
            "status" => 200,
            "message" => "succes",
            "data" => $likes,
        ], 200);
    }

    public function store(Request $request, $articleId)
    {
        $user = auth()->user();
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                "status" => 404,
                "message" => "article not found",
            ], 404);
        }

        $existingLike = $user->likes()->where('article_id', $article->id)->exists();

        if ($existingLike) {
            return response()->json([
                "status" => 400,
                "message" => "you have already liked this article",
            ], 400);
        }

        $like = new Like();
        $like->user()->associate($user);
        $like->article()->associate($article);
        $like->save();

        return response()->json([
            "status" => 200,
            "message" => "like created",
            "data" => $like,
        ], 200);
    }

    public function show($articleId, $likeId)
    {
        $article = Article::find($articleId);
        if (!$article) {
            return response()->json([
                "status" => 404,
                "message" => "article not found",
            ], 404);
        }

        $like = $article->likes()->find($likeId);
        if (!$like) {
            return response()->json([
                "status" => 404,
                "message" => "like not found",
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "message" => "success",
            "data" => $like,
        ], 200);
    }

    public function destroy($articleId, $likeId)
    {
        $user = auth()->user();
        $article = Article::find($articleId);
        if (!$article) {
            return response()->json([
                "status" => 404,
                "message" => "article not found",
            ], 404);
        }

        $like = Like::find($likeId);
        if (!$like) {
            return response()->json([
                "status" => 404,
                "message" => "like not found",
            ], 404);
        }

        if ($like->user_id !== $user->id) {
            return response()->json([
                "status" => 403,
                "message" => "forbidden: You don't have permission to delete this like",
            ], 403);
        }

        $like->delete();

        return response()->json([
            "status" => 200,
            "message" => "Dislike success",
        ], 200);
    }
}
