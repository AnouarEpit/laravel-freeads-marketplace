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

Route::get('/recommended', [AnnonceController::class, 'recommended'])->name('annonces.recommended');

// Rutas de mensajería
Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/sent', [App\Http\Controllers\MessageController::class, 'sent'])->name('messages.sent');
Route::get('/messages/create', [App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/{message}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
Route::get('/contact-user/{userId}', [App\Http\Controllers\MessageController::class, 'contactUser'])->name('contact.user');
