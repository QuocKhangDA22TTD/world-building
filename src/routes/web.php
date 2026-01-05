<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorldController;
use App\Http\Controllers\EntityTypeController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

// Guest routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Worlds
    Route::resource('worlds', WorldController::class);

    // Entity Types
    Route::get('/entity-types', [EntityTypeController::class, 'index'])->name('entity-types.index');
    Route::post('/entity-types', [EntityTypeController::class, 'store'])->name('entity-types.store');
    Route::put('/entity-types/{entityType}', [EntityTypeController::class, 'update'])->name('entity-types.update');
    Route::delete('/entity-types/{entityType}', [EntityTypeController::class, 'destroy'])->name('entity-types.destroy');

    // Entities
    Route::resource('entities', EntityController::class);

    // Relationships
    Route::resource('relationships', RelationshipController::class);

    // Tags
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

    // Admin routes
    Route::middleware('App\Http\Middleware\CheckRole:admin,super_admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
