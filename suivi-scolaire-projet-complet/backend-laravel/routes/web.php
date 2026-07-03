<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\EcoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EnseignantNoteController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\EnseignantAbsenceController;
use App\Http\Controllers\AnnonceController;

// Redirection vers login
Route::get('/', fn() => redirect()->route('login'));

// Authentification commune (admin + enseignant)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ═══════════════════════════════════════════════════
// ROUTES ADMIN UNIQUEMENT
// ═══════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Élèves
    Route::get('/eleves', [EleveController::class, 'index'])->name('eleves.index');
    Route::get('/eleves/create', [EleveController::class, 'create'])->name('eleves.create');
    Route::post('/eleves', [EleveController::class, 'store'])->name('eleves.store');
    Route::get('/eleves/{eleve}', [EleveController::class, 'show'])->name('eleves.show');
    Route::get('/eleves/{eleve}/edit', [EleveController::class, 'edit'])->name('eleves.edit');
    Route::put('/eleves/{eleve}', [EleveController::class, 'update'])->name('eleves.update');
    Route::delete('/eleves/{eleve}', [EleveController::class, 'destroy'])->name('eleves.destroy');

    // Relevé de notes — admin voit tous les élèves
    Route::get('/eleves/{eleve}/releve', [NoteController::class, 'releve'])
         ->name('eleves.releve');

    // Classes
    Route::get('/classes', [ClasseController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClasseController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');
    Route::get('/classes/{classe}', [ClasseController::class, 'show'])->name('classes.show');
    Route::get('/classes/{classe}/edit', [ClasseController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{classe}', [ClasseController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{classe}', [ClasseController::class, 'destroy'])->name('classes.destroy');

    // Matières
    Route::resource('matieres', MatiereController::class);

    // Paiements
    Route::get('/paiements/impayes', [PaiementController::class, 'impayes'])->name('paiements.impayes');
    Route::get('/paiements/{paiement}/recu', [PaiementController::class, 'recu'])->name('paiements.recu');
    Route::resource('paiements', PaiementController::class)->except(['edit', 'update']);

    // Notes — admin voit toutes les classes
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::post('/notes/bulk', [NoteController::class, 'storeBulk'])->name('notes.storeBulk');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('/moyennes', [NoteController::class, 'moyennes'])->name('notes.moyennes');

    // Écoles
    Route::get('/ecoles', [EcoleController::class, 'index'])->name('ecoles.index');
    Route::get('/ecoles/create', [EcoleController::class, 'create'])->name('ecoles.create');
    Route::post('/ecoles', [EcoleController::class, 'store'])->name('ecoles.store');
    Route::post('/ecoles/{ecole}/activer', [EcoleController::class, 'activer'])->name('ecoles.activer');
    Route::delete('/ecoles/{ecole}', [EcoleController::class, 'destroy'])->name('ecoles.destroy');

    // Enseignants
    Route::get('/enseignants', [EnseignantController::class, 'index'])->name('enseignants.index');
    Route::get('/enseignants/create', [EnseignantController::class, 'create'])->name('enseignants.create');
    Route::post('/enseignants', [EnseignantController::class, 'store'])->name('enseignants.store');
    Route::get('/enseignants/{enseignant}/edit', [EnseignantController::class, 'edit'])->name('enseignants.edit');
    Route::put('/enseignants/{enseignant}', [EnseignantController::class, 'update'])->name('enseignants.update');
    Route::delete('/enseignants/{enseignant}', [EnseignantController::class, 'destroy'])->name('enseignants.destroy');

    // Profil admin
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Comptes parents (accès à l'application mobile)
    Route::get('/parents', [ParentController::class, 'index'])->name('parents.index');
    Route::get('/parents/create', [ParentController::class, 'create'])->name('parents.create');
    Route::post('/parents', [ParentController::class, 'store'])->name('parents.store');
    Route::get('/parents/{parent}/edit', [ParentController::class, 'edit'])->name('parents.edit');
    Route::put('/parents/{parent}', [ParentController::class, 'update'])->name('parents.update');
    Route::delete('/parents/{parent}', [ParentController::class, 'destroy'])->name('parents.destroy');

    // Absences — admin voit toutes les classes
    Route::get('/absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::get('/absences/create', [AbsenceController::class, 'create'])->name('absences.create');
    Route::post('/absences', [AbsenceController::class, 'store'])->name('absences.store');
    Route::delete('/absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');

    // Annonces & notifications
    Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
    Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
});

// ═══════════════════════════════════════════════════
// ROUTES ENSEIGNANT UNIQUEMENT
// ═══════════════════════════════════════════════════
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->group(function () {

    // Saisie des notes de SA classe uniquement
    Route::get('/notes', [EnseignantNoteController::class, 'index'])
         ->name('enseignant.notes');

    // Enregistrement en masse des notes
    Route::post('/notes/bulk', [EnseignantNoteController::class, 'storeBulk'])
         ->name('enseignant.notes.bulk');

    // Suppression d'une note
    Route::delete('/notes/{note}', [EnseignantNoteController::class, 'destroy'])
         ->name('enseignant.notes.destroy');

    // Moyennes de SA classe uniquement
    Route::get('/moyennes', [EnseignantNoteController::class, 'moyennes'])
         ->name('enseignant.moyennes');

    // Relevé de notes — enseignant voit uniquement sa classe
    Route::get('/eleves/{eleve}/releve', [EnseignantNoteController::class, 'releve'])
         ->name('enseignant.releve');

    // Absences — enseignant voit et ajoute uniquement pour SA classe
    Route::get('/absences', [EnseignantAbsenceController::class, 'index'])
         ->name('enseignant.absences');
    Route::post('/absences', [EnseignantAbsenceController::class, 'store'])
         ->name('enseignant.absences.store');
    Route::delete('/absences/{absence}', [EnseignantAbsenceController::class, 'destroy'])
         ->name('enseignant.absences.destroy');
});