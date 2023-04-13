<?php

use App\Http\Controllers\Api\AdherentController;
use App\Http\Controllers\Api\CommentaireController;
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

//Route::apiResource('jeux',JeuController::class); // a modif

Route::controller(CommentaireController::class)->group(function () {
    Route::post('commentaire', 'store')->middleware(['auth:api', 'role:adherent'])->name("commentaire.post");
    Route::match(['put', 'patch'], 'commentaire/{id}', 'update')->middleware(['auth:api'])->name("commentaire.update");
    Route::delete('commentaire/{id}', 'destroy')->middleware(['auth:api'])->name("commentaire.destroy");
});

Route::controller(JeuController::class)->group(function () {
//    Route::get('jeu', 'index')->name("jeu.index");
    Route::post('jeu/listeJeu', 'listeJeu')->name("jeu.liste");
    Route::post('jeu', 'store')->middleware(['auth:api', 'role:adhérent-premium'])->name("jeu.store");
    Route::match(['put', 'patch'],'jeu/{id}', 'update')->middleware(['auth:api', 'role:adhérent-premium'])->name("jeu.store");
    Route::match(['put', 'patch'],'jeu/{id}/url', 'modifUrl')->middleware(['auth:api', 'role:adhérent-premium'])->name("jeu.url");
    Route::post('jeu/achat', 'achatJeu')->middleware(['auth:api', 'role:adhérent-premium'])->name("jeu.achat");
    Route::delete('jeu/achat/{id}', 'supprimerAchat')->middleware(['auth:api'])->name("jeu.suprAchat");
    Route::get('jeu/{id}', 'detailJeu')->middleware(['auth:api'])->name("jeu.details");
});

//Route::post('jeux/listejeu', [JeuController::class,'listeJeu'])->name('jeux.listeJeu');
