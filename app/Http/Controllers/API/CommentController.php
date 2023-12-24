<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Validator;

class CommentController extends Controller
{
    public function index($articleId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $comments = $article->comments;

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $comments,
        ]);
    }

    public function show($articleId, $commentId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $comment = $article->comments()->find($commentId);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $comment,
        ], 200);
    }

    public function store(Request $request, $articleId)
    {
        $user = auth()->user();
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error!',
                'data' => $validator->errors(),
            ], 422);
        }

        $comment = new Comment([
            'body' => $request->input('body'),
        ]);

        $comment->user()->associate($user);
        $comment->article()->associate($article);
        $comment->save();

        return response()->json([
            'status' => 201,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }

    public function update(Request $request, $articleId, $commentId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error!',
                'data' => $validator->errors(),
            ], 422);
        }

        $comment->update([
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ]);
    }

    public function destroy($articleId, $commentId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
