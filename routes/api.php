<?php

use App\Http\Controllers\Api\AdherentController;
use App\Http\Controllers\Api\JeuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AdherentController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('logout', 'logout');
    Route::get('info/{id}', 'info');
    Route::post('edit/{id}', 'edit');
    Route::post('avatar/{id}', 'avatar');
});

Route::apiResource('jeux',JeuController::class); // a modif

Route::post('jeux/listejeu', [JeuController::class,'listeJeu'])->name('jeux.listeJeu');
