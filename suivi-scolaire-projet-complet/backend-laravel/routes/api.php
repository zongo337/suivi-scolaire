<?php

use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EleveController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\PaiementController;
use Illuminate\Support\Facades\Route;

/*
 * Toutes ces routes sont préfixées par /api (voir bootstrap/app.php).
 * Authentification : Laravel Sanctum (token Bearer).
 */

// ═══════════════════════════════════════════════════
// AUTHENTIFICATION PARENT (publique)
// ═══════════════════════════════════════════════════
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

// ═══════════════════════════════════════════════════
// ROUTES PROTÉGÉES (parent authentifié)
// ═══════════════════════════════════════════════════
Route::middleware(['auth:sanctum', 'parent.api'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/password', [AuthController::class, 'updatePassword']);

    // Sélecteur d'enfant (un parent peut avoir plusieurs enfants)
    Route::get('/eleves', [EleveController::class, 'index']);

    // Tableau de bord d'un élève
    Route::get('/eleves/{eleve}/dashboard', [DashboardController::class, 'show']);

    // Notes
    Route::get('/eleves/{eleve}/notes', [NoteController::class, 'index']);

    // Paiements
    Route::get('/eleves/{eleve}/paiements', [PaiementController::class, 'index']);
    Route::get('/eleves/{eleve}/paiements/{paiement}/recu', [PaiementController::class, 'recu']);

    // Absences
    Route::get('/eleves/{eleve}/absences', [AbsenceController::class, 'index']);

    // Annonces & notifications
    Route::get('/annonces', [AnnonceController::class, 'index']);
});
