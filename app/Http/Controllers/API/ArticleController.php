<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $articles,
        ]);
    }

    public function indexCurrentUser()
    {
        $user = auth()->user();
        $articles = $user->articles;

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $articles,
        ]);
    }

    public function show($id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $article,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error!',
                'data' => $validator->errors(),
            ], 422);
        }

        $article = auth()->user()->articles()->create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Article created successfully',
            'data' => $article,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error!',
                'data' => $validator->errors(),
            ], 422);
        }

        $article->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Article updated successfully',
            'data' => $article,
        ]);
    }

    public function destroy($id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
            ], 404);
        }

        $article->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Article deleted successfully',
        ]);
    }
}
