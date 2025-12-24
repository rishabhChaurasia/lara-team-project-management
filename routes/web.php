<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {

    // project routes
    // Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    // Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    // Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    // Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    // Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    // Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    // Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    // Route::get('/projects/{project}/members', [ProjectController::class, 'members'])->name('projects.members');
    // Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.addMember');
    // Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');
});

require __DIR__ . '/auth.php';
