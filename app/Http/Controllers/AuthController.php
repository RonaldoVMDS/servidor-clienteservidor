<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthController extends Controller
{
     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try{

            $credentials = $request->only(['email', 'password']);
            // $credentials['password'] = md5($request->password);

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Essas credenciais não correspondem aos nossos registros.'], 401);
            }
            $user = auth()->user();
            $id = $user->id;
            $username = $user->name;
            $email = $user->email;

            // Criar um payload personalizado com o ID do usuário
            $customPayload = ['user_id' => $id];

            // Gerar o token JWT com o payload personalizado
            $token = JWTAuth::fromUser($user, $customPayload);

            return response()->json([
                'id' => $id,
                'name' => $username,
                'email' => $email,
                'token' => $token
            ], 200);
        }
        catch(JWTException $e){
            // Retorna uma mensagem de erro genérico caso o erro seja outro
            return response()->json(['message' => 'Erro ao tentar encontrar o usuário no servidor'], 500);
        }
    }

     /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
            
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         // 'expires_in' => JWTFactory::getTTL() * 60
    //     ]);
    // }
}
