<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BonAchatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/module/{module}', [ModuleController::class, 'show'])->name('module.show')->where('module', '[a-z0-9.\-]+');

    Route::prefix('fournisseurs')->name('fournisseurs.fiche.')->group(function () {
        Route::get('fiche', [FournisseurController::class, 'index'])->name('index');
        Route::post('fiche', [FournisseurController::class, 'store'])->name('store');
        Route::put('fiche/{fournisseur}', [FournisseurController::class, 'update'])->name('update');
        Route::delete('fiche/{fournisseur}', [FournisseurController::class, 'destroy'])->name('destroy');
        Route::get('fiche/{fournisseur}/print', [FournisseurController::class, 'print'])->name('print');
        Route::get('fiche/print-all', [FournisseurController::class, 'printAll'])->name('print-all');
        Route::get('fiche/export-pdf', [FournisseurController::class, 'exportPdf'])->name('export-pdf');
    });

    Route::prefix('fournisseurs')->name('fournisseurs.bons-achats.')->group(function () {
        Route::get('bons-achats', [BonAchatController::class, 'index'])->name('index');
        Route::post('bons-achats', [BonAchatController::class, 'store'])->name('store');
        Route::put('bons-achats/{bonAchat}', [BonAchatController::class, 'update'])->name('update');
        Route::delete('bons-achats/{bonAchat}', [BonAchatController::class, 'destroy'])->name('destroy');
        Route::get('bons-achats/{bonAchat}/print', [BonAchatController::class, 'print'])->name('print');
        Route::get('bons-achats/{bonAchat}/export-pdf', [BonAchatController::class, 'exportPdf'])->name('export-pdf');
    });

    Route::prefix('fournisseurs')->name('fournisseurs.reglement.')->group(function () {
        Route::get('reglement', [ReglementController::class, 'index'])->name('index');
        Route::get('reglement/nouveau', [ReglementController::class, 'create'])->name('create');
        Route::get('reglement/fournisseur/{fournisseur}/solde', [ReglementController::class, 'soldeFournisseur'])->name('solde');
        Route::post('reglement', [ReglementController::class, 'store'])->name('store');
        Route::get('reglement/{reglement}/modifier', [ReglementController::class, 'edit'])->name('edit');
        Route::put('reglement/{reglement}', [ReglementController::class, 'update'])->name('update');
        Route::delete('reglement/{reglement}', [ReglementController::class, 'destroy'])->name('destroy');
        Route::get('reglement/{reglement}/voir', [ReglementController::class, 'show'])->name('show');
        Route::get('reglement/{reglement}/print', [ReglementController::class, 'print'])->name('print');
    });

    Route::prefix('fournisseurs')->name('fournisseurs.balance.')->group(function () {
        Route::get('balance', [BalanceController::class, 'index'])->name('index');
    });

    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('products/{product}/movement', [ProductController::class, 'movement'])->name('products.movement');
    });

    Route::resource('tasks', TaskController::class)->except(['destroy']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('systeme/utilisateurs', [SystemUserController::class, 'index'])->name('systeme.utilisateurs.index');
        Route::post('systeme/utilisateurs', [SystemUserController::class, 'store'])->name('systeme.utilisateurs.store');
        Route::delete('systeme/utilisateurs/{user}', [SystemUserController::class, 'destroy'])->name('systeme.utilisateurs.destroy');

        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});
