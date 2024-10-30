<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommentsController extends Controller
{
    // Retorna todos os comentários de um post
    public function index(Post $post)
    {
        return response()->json($post->comments); 
    }

    // Cria um novo comentário
    public function store(Request $request, Post $post)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        // Criação do comentário
        $comment = Comment::create([
            'id' => (string) Str::uuid(),
            'content' => $validatedData['content'],
            'user_id' => Auth::id(), // Assumindo que o usuário está autenticado
            'post_id' => $post->id,
        ]);

        return response()->json($comment, 201);
    }

    // Retorna um comentário pelo ID
    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    // Atualiza um comentário
    public function update(Request $request,$id)
    {   
            $comment = Comment::findOrFail($id);
        // Verifica se o usuário logado é o autor do comentário
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }

        // Valida os dados
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);
    

        // Atualiza os dados do comentário
        $comment->update($validatedData);

        return response()->json($comment, 200);
    }

    // Deleta um comentário
    public function destroy(Comment $comment)
    {
        // Verifica se o usuário logado é o autor do comentário
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }

        // Deleta o comentário
        $comment->delete();

        return response()->json(null, 204);
    }
}

