<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnonceController;
use Illuminate\Support\Facades\Auth;

// Ruta principal
Route::get('/', [IndexController::class, 'showIndex']);

// Rutas de autenticación con verificación
Auth::routes(['verify' => true]);

// Ruta del dashboard con verificación
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware(['auth', 'verified']);

// Rutas del perfil
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Rutas de los anuncios
Route::resource('annonces', AnnonceController::class);
