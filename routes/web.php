<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::redirect('/', '/login');

// Authentication routes (public)
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);

// Protected routes (require login)
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Habits CRUD
    Route::get('/habits',             [HabitController::class, 'index']);
    Route::get('/habits/create',      [HabitController::class, 'create']);
    Route::post('/habits',            [HabitController::class, 'store']);
    Route::get('/habits/{id}',        [HabitController::class, 'show']);
    Route::get('/habits/{id}/edit',   [HabitController::class, 'edit']);
    Route::put('/habits/{id}',        [HabitController::class, 'update']);
    Route::delete('/habits/{id}',     [HabitController::class, 'destroy']);
    Route::post('/habits/{id}/log',   [HabitController::class, 'log']);

    // Profile
    Route::get('/profile',             [ProfileController::class, 'index']);
    Route::get('/profile/edit',        [ProfileController::class, 'edit']);
    Route::put('/profile',             [ProfileController::class, 'update']);
    Route::put('/profile/password',    [ProfileController::class, 'updatePassword']);
});
