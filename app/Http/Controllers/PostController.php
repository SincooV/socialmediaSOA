<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Retorna todos os posts
    public function index(){
        $posts = Post::all();
        return response()->json($posts);
    }

    // Armazena um novo post
    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validatedData = $request->validate([
            'title' => 'required|string',
        ]);

        // Associa o post ao usuário logado
        $post = new Post();
        $post->title = $validatedData['title'];
        $post->user_id = Auth::id();  // Associa o post ao usuário autenticado
        $post->save();

        return response()->json($post, 201);
    }

    // Mostra um post específico
    public function show($id)
    {
        // Busca o post pelo UUID
        $post = Post::with('user', 'comments')->findOrFail($id);
        return response()->json($post);
    }

    // Atualiza um post existente
    public function update(Request $request, $id)
    {
        // Valida os dados de entrada
        $validatedData = $request->validate([
            'title' => 'required|string',
        ]);

        // Busca o post pelo UUID e o atualiza
        $post = Post::findOrFail($id);
        $post->update($validatedData);

        return response()->json($post, 200);
    }

    // Exclui um post existente
    public function destroy($id)
    {
        // Busca o post pelo UUID e o deleta
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
}
