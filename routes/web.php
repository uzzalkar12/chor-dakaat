<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/', fn() => auth()->check() ? redirect()->route('lobby') : view('welcome'))->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Game routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/lobby', [GameController::class, 'lobby'])->name('lobby');
    Route::post('/room/create', [GameController::class, 'createRoom'])->name('room.create');
    Route::post('/room/join', [GameController::class, 'joinRoom'])->name('room.join');
    Route::get('/room/{roomCode}', [GameController::class, 'room'])->name('game.room');

    // API endpoints
    Route::post('/api/room/{roomCode}/start', [GameController::class, 'startGame'])->name('api.game.start');
    Route::post('/api/room/{roomCode}/guess', [GameController::class, 'policeGuess'])->name('api.game.guess');
    Route::post('/api/room/{roomCode}/next-round', [GameController::class, 'nextRound'])->name('api.game.next');
    Route::post('/api/room/{roomCode}/leave', [GameController::class, 'leaveRoom'])->name('api.game.leave');
    Route::get('/api/room/{roomCode}/state', [GameController::class, 'roomState'])->name('api.game.state');
});
