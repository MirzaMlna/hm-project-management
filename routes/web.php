<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerCategoryController;
use App\Http\Controllers\WorkerController;
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
    // Worker Types
    Route::resource('worker-categories', WorkerCategoryController::class);
    // Worker Management
    Route::post('/workers/{worker}/deactivate', [WorkerController::class, 'deactivate'])->name('workers.deactivate');
    Route::get('/workers/inactive', [WorkerController::class, 'inactive'])->name('workers.inactive');
    Route::post('/workers/{worker}/activate', [WorkerController::class, 'activate'])->name('workers.activate');
    Route::resource('workers', WorkerController::class);
    // Worker Presence Schedule
    Route::resource('worker-presence-schedules', \App\Http\Controllers\WorkerPresenceScheduleController::class);
});

require __DIR__ . '/auth.php';
