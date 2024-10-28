<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Retorna todos os usuários
    public function index()
    {
        $Users = User::all();
        return response()->json($Users);
    }

    // Cria um novo usuário
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed' // Verifica se as senhas batem (password + password_confirmation)
        ]);

        // Se a validação falhar, lança uma exceção
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        // Criação do usuário
        try {
            $User = User::create([
                'id' => (string) Str::uuid(),
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password) // Armazena a senha de forma segura
            ]);

            // Retorna a resposta com status 201
            return response()->json($User, 201);

        } catch (\Exception $e) {
            // Retorna uma resposta de erro com status 500
            return response()->json(['error' => 'Erro ao criar o usuário', 'details' => $e->getMessage()], 500);
        }
    }

    // Login do usuário
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($data)) {
            return response()->json(['email' => 'Credenciais inválidas.'], 401);

        }
       


        $student = Auth::user();

        // Cria o token de acesso pessoal
        $token = $student->createToken('Personal Access Token', ['*'], now()->addDays(30))->plainTextToken;

        return response()->json([
            'message' => 'Login bem-sucedido.',
            'token' => $token,
            'User' => $student,
        ]);
    }

    // Logout do usuário
    public function logout(Request $request)
    {
        // Revoga o token atual do usuário
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    // Retorna um usuário pelo ID
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, User $User)
    {
        // Verifica se o usuário logado é o mesmo que está tentando atualizar
        if (Auth::id() !== $User->id) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }

        // Valida os dados
        $validatedData = $request->validate([
            'username' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        // Atualiza os dados do usuário
        $User->update([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']) // Atualiza a senha de forma segura
        ]);

        return response()->json($User, 200);
    }

    public function destroy(User $User)
    {
        // Verifica se o usuário logado é o mesmo que está tentando deletar
        if (Auth::id() !== $User->id) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }

        // Deleta o usuário
        $User->delete();

        return response()->json(null, 204);
    }


    
}