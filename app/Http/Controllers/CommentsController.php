<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index($postId)
    {
        return Comment::where('post_id', $postId)->with('user')->get(); 
    }

    public function show($id)
    {
        return Comment::findOrFail($id);
    }

    public function store(Request $request)
    {
        $comment = Comment::create($request->all());
        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());
        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        Comment::destroy($id);
        return response()->json(null, 204);
    }
}
