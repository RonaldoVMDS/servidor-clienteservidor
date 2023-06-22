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



Route::group(['middleware' => ['apiJwt']], function () {
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('/occurrences', [OccurrenceController::class, 'createOccurrence']);
    Route::get('occurrences/users/{id}', [OccurrenceController::class, 'getUserOccurrences']);
    Route::put('occurrences/{occurrenceId}', [OccurrenceController::class, 'updateOccurrence']);
    Route::delete('occurrences/{occurrenceId}', [OccurrenceController::class, 'deleteOccurrence']);
    Route::post('logout', [UserController::class, 'logout']);
});
Route::get('/occurrences', [OccurrenceController::class, 'getAllOccurrences']);
Route::post('login', [AuthController::class, 'login']);
Route::post('users', [UserController::class, 'store']);
// falta desenvolver também toda a tela de ocorrências