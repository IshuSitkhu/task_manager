<?php

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

    Route::resource('projects', ProjectController::class);
    
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMembers'])
    ->name('projects.addMembers');

    Route::get('/projects/{project}/overview', [ProjectController::class, 'overview'])->name('projects.overview');

    Route::get('/projects/{project}/epics', [ProjectController::class, 'epics'])->name('projects.epics');

    Route::get('/projects/{project}/sprints', [ProjectController::class, 'sprints'])->name('projects.sprints');

    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');
});

require __DIR__.'/auth.php';
