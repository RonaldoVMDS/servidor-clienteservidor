<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CadastroController extends Controller
{
    public function cadastrar(Request $request)
    {
        $nome = $request->input('name');
        $email = $request->input('email');
        $senha = $request->input('password');

        // Montar os dados da requisição para a API
        $data = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha
        ];

        // Enviar a requisição para a API usando o GuzzleHttp
        $apiServer = env('API_SERVER');
        $client = new Client(['base_uri' => $apiServer]);
        $response = $client->request('POST', '/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $data
        ]);

        // Verificar a resposta da API
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        
        // Se a API retornar um código 201, significa que o usuário foi criado com sucesso
        if ($statusCode == 201) {
            return view('sucesso');
        } else {
            return view('erro', ['mensagem' => 'Não foi possível cadastrar o usuário.']);
        }
    }

}
