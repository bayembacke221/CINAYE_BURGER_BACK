<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BurgerController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\Api\PaiementController;
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

Route::post('v1/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('v1/logout', [AuthController::class, 'logout']);
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Routes personnalisées pour CommandeController
    Route::put('commandes/{id}/change-etat', [CommandeController::class, 'changeEtat'])->name('commandes.changeEtat');
    Route::put('commandes/{id}/annuler', [CommandeController::class, 'annuler'])->name('commandes.annuler');
    Route::get('commandes/statistiques-jour', [CommandeController::class, 'statistiquesJour'])->name('commandes.statistiquesJour');
    Route::get('commandes/en-cours', [CommandeController::class, 'getCommandesEnCours'])->name('commandes.enCours');
    Route::get('commandes/validees', [CommandeController::class, 'getCommandesValidees'])->name('commandes.validees');
    Route::get('commandes/annulees', [CommandeController::class, 'getCommandesAnnulees'])->name('commandes.annulees');
    Route::get('commandes/journalieres/{date}', [CommandeController::class, 'getCommandesJournalieres'])->name('commandes.journalieres');
    Route::get('commandes/recettes-journalieres/{date}', [CommandeController::class, 'getRecettesJournalieres'])->name('commandes.recettesJournalieres');
    Route::get('commandes/by-client/{clientId}', [CommandeController::class, 'getCommandesByClient'])->name('commandes.byClient');

    // Autres routes personnalisées
    Route::get('clients/{id}/commandes', [ClientController::class, 'getClientCommandes'])->name('clients.commandes');
    Route::put('burgers/{id}/archive', [BurgerController::class, 'archive'])->name('burgers.archive');
    Route::get('paiements/date/{date}', [PaiementController::class, 'getPaiementsByDate'])->name('paiements.byDate');
    Route::get('paiements/total-by-date/{date}', [PaiementController::class, 'getTotalPaiementsByDate'])->name('paiements.totalByDate');
    Route::get('paiements/by-commande/{id}', [PaiementController::class, 'getPaiementByCommandeId'])->name('paiements.byCommande');

    // Routes de ressource
    Route::resource('commandes', CommandeController::class)->except(['show']);
    Route::resource('clients', ClientController::class);
    Route::resource('burgers', BurgerController::class);
    Route::resource('paiements', PaiementController::class);
});


