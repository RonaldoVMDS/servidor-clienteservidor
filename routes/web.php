<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CadastroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('cliente');
});

Route::get('cliente/cadastro', function () {
    return view('cadastro');
});
Route::post('/cadastro', [CadastroController::class, 'cadastrar']);


// Route::get('users', [UserController::class, 'index']);