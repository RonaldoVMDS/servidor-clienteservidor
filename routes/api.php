<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::group(['middleware'=>['apiJwt']], function(){
    Route::get('userson', [UserController::class, 'index']);
});
Route::post('login', [AuthController::class, 'login']);
Route::get('logout/{id}', [UserController::class, 'logout']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']); // Solicita os dados do usuário que possui o ID informado para editar - desenvolver
Route::put('users/{id}', [UserController::class, 'update']); // Realiza a atualização do cadastro no sistema - desenvolver
Route::delete('users/{id}', [UserController::class, 'destroy']); // Deleta o cadastro do sistema - desenvolver
// falta desenvolver também toda a tela de ocorrências