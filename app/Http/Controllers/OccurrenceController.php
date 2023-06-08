<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OccurrenceController extends Controller
{
    public function getAllOccurrences()
    {
        $occurrences = Occurrence::all();

        return response()->json($occurrences);
    }

    public function getUserOccurrences(Request $request, $idRequest)
    {
        try {
            $token = $request->bearerToken(); // Obtém o token do cabeçalho de autenticação

            if (!$token) {
                return response()->json(['message' => 'Token não fornecido.'], 401);
            }

            $payload = JWTAuth::parseToken()->getPayload();
            $userId = $payload->get('sub'); // Obtém o ID do usuário do token
            $id = $idRequest;
            if ($userId == $id) {
                if (JWTAuth::parseToken($token)->check()) {
                    // Aqui fica a lógica de retorno em caso de sucesso
                    $user = User::find($id);

                    if (!$user) {
                        return response()->json(['message' => 'Usuário não encontrado.'], 404);
                    }

                    // Buscar as ocorrências do usuário
                    $occurrences = Occurrence::where('user_id', $id)->get();

                    return response()->json(['occurrences' => $occurrences], 200);
                } else {
                    // Retorna mensagem de erro caso o token seja inválido
                    return response()->json(['message' => 'Token inválido.'], 401);
                }
            } else {
                // Retorna mensagem de erro caso não encontre o ID do usuário no token
                return response()->json(['message' => 'ID do usuário não encontrado no token ou não é válido.'], 401);
            }
        } catch (JWTException $e) {
            // Retorna uma mensagem de erro genérico caso ocorra uma exceção
            return response()->json(['message' => "Erro no servidor: $e"], 500);
        }
    }

    public function createOccurrence(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'registered_at' => [
                        'required',
                        'date_format:Y-m-d\TH:i:s.v\Z',
                        function ($attribute, $value, $fail) {
                            $now = Carbon::now('UTC');
                            $inputDate = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $value, 'UTC');
                    
                            if ($inputDate->greaterThan($now)) {
                                $fail('A data de registro não pode ser uma data futura.');
                            }
                        },
                    ],
                                                        
                    'local' => 'required|string|min:2|max:125',
                    'occurrence_type' => 'required|integer|between:1,10',
                    'km' => 'required|integer|between:1,9999',
                    'user_id' => 'required|integer',
                ],
                [
                    'registered_at.required' => 'A data de registro é obrigatória.',
                    'registered_at.date_format' => 'O formato da data de registro deve ser ISO.',
                    'local.required' => 'O local é obrigatório.',
                    'local.string' => 'O local deve ser uma string.',
                    'local.min' => 'Favor preencher com ao menos :min caracteres.',
                    'local.max' => 'Favor preencher com no máximo :max caracteres.',
                    'occurrence_type.required' => 'O tipo de ocorrência é obrigatório.',
                    'occurrence_type.integer' => 'O tipo de ocorrência deve ser um número inteiro.',
                    'occurrence_type.between' => 'O tipo de ocorrência não foi identificado.',
                    'km.required' => 'O valor de quilometragem é obrigatório.',
                    'km.integer' => 'O valor de quilometragem deve ser um número inteiro.',
                    'km.between' => 'A quilometragem não pode ser zero ou extrapolou o limite máximo.',
                    'user_id.required' => 'O ID do usuário é obrigatório.',
                    'user_id.integer' => 'O ID do usuário deve ser um número inteiro.',
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'message' => $errors->first()
                ], 400);
            }
            $token = $request->bearerToken(); // Obtém o token do cabeçalho de autenticação

            if (!$token) {
                return response()->json(['message' => 'Token não fornecido.'], 401);
            }

            $payload = JWTAuth::parseToken()->getPayload();
            $userId = $payload->get('sub'); // Obtém o ID do usuário do token
            $id = $request->input('user_id');
            if ($userId == $id) {
                if (JWTAuth::parseToken($token)->check()) {
                    // Aqui fica a lógica de retorno em caso de sucesso
                    $user = User::find($id);

                    if (!$user) {
                        return response()->json(['message' => 'Usuário não encontrado.'], 401);
                    }
                    $validatedData = $validator->validated();

                    $occurrence = Occurrence::create($validatedData);


                    return response()->json($occurrence, 201);
                } else {
                    // Retorna mensagem de erro caso o token seja inválido
                    return response()->json(['message' => 'Token inválido.'], 401);
                }
            } else {
                // Retorna mensagem de erro caso não encontre o ID do usuário no token
                return response()->json(['message' => 'ID do usuário não encontrado no token ou não é válido.'], 401);
            }
        } catch (JWTException $e) {
            // Retorna uma mensagem de erro genérico caso ocorra uma exceção
            return response()->json(['message' => "Erro no servidor: $e"], 500);
        }
    }
    public function updateOccurrence(Request $request, string $ocrId)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'registered_at' => [
                        'required',
                        'date_format:Y-m-d\TH:i:s.u\Z',
                        function ($attribute, $value, $fail) {
                            $now = Carbon::now();
                            $inputDate = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $value);

                            if ($inputDate->greaterThan($now)) {
                                $fail('A data de registro não pode ser uma data futura.');
                            }
                        },
                    ],
                    'local' => 'required|string|min:10|max:125',
                    'occurrence_type' => 'required|integer|between:1,10',
                    'km' => 'required|integer|between:1,9999',
                    'user_id' => 'required|integer',
                ],
                [
                    'registered_at.required' => 'A data de registro é obrigatória.',
                    'registered_at.date_format' => 'O formato da data de registro deve ser YYYY-MM-DD HH:mm:ss.',
                    'local.required' => 'O local é obrigatório.',
                    'local.string' => 'O local deve ser uma string.',
                    'local.min' => 'Favor preencher com ao menos :min caracteres.',
                    'local.max' => 'Favor preencher com no máximo :max caracteres.',
                    'occurrence_type.required' => 'O tipo de ocorrência é obrigatório.',
                    'occurrence_type.integer' => 'O tipo de ocorrência deve ser um número inteiro.',
                    'occurrence_type.between' => 'O tipo de ocorrência não foi identificado.',
                    'km.required' => 'O valor de quilometragem é obrigatório.',
                    'km.integer' => 'O valor de quilometragem deve ser um número inteiro.',
                    'km.between' => 'A quilometragem não pode ser zero ou extrapolou o limite máximo.',
                    'user_id.required' => 'O ID do usuário é obrigatório.',
                    'user_id.integer' => 'O ID do usuário deve ser um número inteiro.',
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'message' => $errors->first()
                ], 400);
            }
            $token = $request->bearerToken(); // Obtém o token do cabeçalho de autenticação

            if (!$token) {
                return response()->json(['message' => 'Token não fornecido.'], 401);
            }

            $payload = JWTAuth::parseToken()->getPayload();
            $userId = $payload->get('sub'); // Obtém o ID do usuário do token
            $id = $request->input('user_id');
            if ($userId == $id) {
                if (JWTAuth::parseToken($token)->check()) {
                    // Aqui fica a lógica de retorno em caso de sucesso
                    $user = User::find($id);
                    if (!$user) {
                        return response()->json(['message' => 'Usuário não encontrado.'], 404);
                    }
                    $occurrence = Occurrence::findOrFail($ocrId);

                    // Verificar se a ocorrência pertence ao usuário autenticado antes de permitir a atualização
                    if ($occurrence->user_id != $id) {
                        return response()->json(['message' => 'Você não tem permissão para atualizar esta ocorrência.'], 403);
                    }
                    $validatedData = $validator->validated();

                    $occurrence->update($validatedData);

                    return response()->json($occurrence, 200);
                } else {
                    // Retorna mensagem de erro caso o token seja inválido
                    return response()->json(['message' => 'Token inválido.'], 401);
                }
            } else {
                // Retorna mensagem de erro caso não encontre o ID do usuário no token
                return response()->json(['message' => 'ID do usuário não encontrado no token ou não é válido.'], 401);
            }
        } catch (JWTException $e) {
            // Retorna uma mensagem de erro genérico caso ocorra uma exceção
            return response()->json(['message' => "Erro no servidor: $e"], 500);
        }
    }
}
