<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:125',
                'email' => 'required|string|min:10|max:125|email|unique:users',
                'password' => 'required|string|min:2',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['message' => 'As credenciais informadas não correspondem ao modelo correto da requisição. Por favor verifique os dados informados e tente novamente.'], 400);
            }

            $data = $request->validate([
                'name' => 'required|string|min:2|max:125',
                'email' => 'required|string|min:10|max:125',
                'password' => 'required|string|min:2',
            ]);
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $id = $user->id;
            $name = $user->name;
            $email = $user->email;

            return response()->json([
                'id' => $id,
                'name' => $name,
                'email' => $email,
            ], 201);// Adiciona o status
        }
        catch(JWTException $e){
            // Retorna uma mensagem de erro genérico caso o erro seja outro
            return response()->json(['message' => 'Erro ao tentar cadastrar o usuário no servidor'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json([

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function logout(Request $request, string $id){
        try {
            // Busca o usuário pelo ID
            $user = User::findOrFail($id);
            $token = JWTAuth::fromUser($user);

            // Verifique se o token é válido
            if (JWTAuth::parseToken($token)->check()){
                // Revoga o token JWT do usuário
                JWTAuth::invalidate(JWTAuth::getToken());
        
                // Retorna uma mensagem de sucesso
                return response()->json(['message' => 'Logout realizado com sucesso!'], 200);
            }
            else{
                // Retorna mensagem de erro caso não encontre o id no sistema
                return response()->json(['message' => 'Essas credenciais não correspondem aos nossos registros.'], 401);
            }
    
        } catch (JWTException $e) {
            // Retorna uma mensagem de erro genérico caso o erro seja outro
            return response()->json(['message' => 'Erro ao tentar encontrar o usuário no servidor'], 500);
        }
    }
}
