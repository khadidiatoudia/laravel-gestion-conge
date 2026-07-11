<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\JourFerieController;
use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Route;

// La racine affiche la page d'accueil publique de l'application.
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes pour les invités (Non connectés)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes protégées (Nécessitent d'être connecté)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/mon-compte', [AuthController::class, 'account'])->name('user.dashboard');
    Route::middleware('admin')->group(function () {
        Route::resource('agents', AgentController::class);
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::post('/agents/{agent}/link-user', [AgentController::class, 'attachUser'])->name('agents.linkUser');
        Route::delete('/agents/{agent}/unlink-user', [AgentController::class, 'detachUser'])->name('agents.unlinkUser');

        // Rapports & Exports PDF / Excel
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/{lieu}', [RapportController::class, 'generer'])->name('rapports.generer');
        Route::get('/rapports/{lieu}/pdf', [RapportController::class, 'exportPdf'])->name('rapports.pdf');
        Route::get('/rapports/{lieu}/csv', [RapportController::class, 'exportCsv'])->name('rapports.csv');
        Route::get('/rapports-global/pdf', [RapportController::class, 'exportAll'])->name('rapports.all');
        Route::get('/rapports-global/csv', [RapportController::class, 'exportAllCsv'])->name('rapports.all.csv');

        // Jours Fériés
        Route::get('/jours-feries', [JourFerieController::class, 'index'])->name('jours_feries.index');
        Route::post('/jours-feries', [JourFerieController::class, 'store'])->name('jours_feries.store');
        Route::delete('/jours-feries/{jourFerie}', [JourFerieController::class, 'destroy'])->name('jours_feries.destroy');
    });

    // Gestion des Absences (admin ou utilisateur lié)
    Route::get('/agents/{agent}/absences/create', [AbsenceController::class, 'create'])->name('absences.create');
    Route::post('/agents/{agent}/absences', [AbsenceController::class, 'store'])->name('absences.store');
    Route::delete('/absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');

    // Gestion des Congés (admin ou utilisateur lié)
    Route::get('/agents/{agent}/conges/create', [CongeController::class, 'create'])->name('conges.create');
    Route::post('/agents/{agent}/conges', [CongeController::class, 'store'])->name('conges.store');
    Route::patch('/conges/{conge}/statut', [CongeController::class, 'updateStatut'])->name('conges.statut');
    Route::delete('/conges/{conge}', [CongeController::class, 'destroy'])->name('conges.destroy');
});
