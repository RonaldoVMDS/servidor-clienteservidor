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
            ],
            [
                'name.required' => 'O Nome é obrigatório',
                'name.min' => 'O nome deve ter no mínimo :min caracteres',
                'name.max' => 'O nome deve ter no mínimo :max caracteres',
                'name.string' => 'O nome deve ser do tipo string',
                'email.required' => 'O E-mail é obrigatório',
                'email.min' => 'O e-mail deve ter no mínimo :min caracteres',
                'email.max' => 'O e-mail deve ter no máximo :max caracteres',
                'email.email' => 'O e-mail deve ser um endereço de e-mail válido',
                'email.unique' => 'O e-mail fornecido já está sendo usado',
                'password.required' => 'A senha é obrigatória',
                'password.min' => 'A senha deve ter no mínimo :min caracteres',
                'password.string' => 'A senha deve ser do tipo string',
            ]
        );
            
        
        if ($validator->fails()) {
            $errors = $validator->errors();
        
            if ($errors->has('email') && $errors->first('email') === 'O e-mail fornecido já está sendo usado') {
                return response()->json([
                    'message' => $errors->first('email')
                ], 422);
            } else {
                return response()->json([
                    'message' => $errors->first()
                ], 400);
            }
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

    public function logout(Request $request){
        try {
            $token = $request->bearerToken(); // Obtém o token do cabeçalho de autenticação
    
            if (!$token) {
                return response()->json(['message' => 'Token não fornecido.'], 401);
            }
    
            $payload = JWTAuth::parseToken()->getPayload();
            $userId = $payload->get('sub'); // Obtém o ID do usuário do token
            $id = $request->input('id');
            if ($userId == $id) {
                // Busca o usuário pelo ID
                $user = User::findOrFail($userId);
    
                // Verifique se o token é válido
                if (JWTAuth::parseToken($token)->check()){
                    // Revoga o token JWT do usuário
                    JWTAuth::invalidate(JWTAuth::getToken());
            
                    // Retorna uma mensagem de sucesso
                    return response()->json(['message' => 'Logout realizado com sucesso!'], 200);
                }
                else {
                    // Retorna mensagem de erro caso o token seja inválido
                    return response()->json(['message' => 'Token inválido.'], 401);
                }
            }
            else {
                // Retorna mensagem de erro caso não encontre o ID do usuário no token
                return response()->json(['message' => 'ID do usuário não encontrado no token.'], 401);
            }
        } catch (JWTException $e) {
            // Retorna uma mensagem de erro genérico caso ocorra uma exceção
            return response()->json(['message' => 'Erro ao processar o token.'], 500);
        }
    }
    
}
