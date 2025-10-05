<?php

use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerBonusController;
use App\Http\Controllers\WorkerCategoryController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerPresenceController;
use App\Http\Controllers\WorkerPresenceScheduleController;
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
    Route::get('/workers/print-all', [WorkerController::class, 'printAllIdCards'])->name('workers.printAll');
    Route::post('/workers/import', [WorkerController::class, 'import'])->name('workers.import');
    Route::resource('workers', WorkerController::class);
    // Worker Presence Schedule
    // Worker Presence Schedule
    Route::resource('worker-presence-schedules', WorkerPresenceScheduleController::class)
        ->except(['store']); // biar POST tidak otomatis diarahkan ke store()
    Route::post('worker-presence-schedules', [WorkerPresenceScheduleController::class, 'storeOrUpdate'])
        ->name('worker-presence-schedules.save');

    // Worker Presence (Scan QR)
    Route::resource('worker-presences', WorkerPresenceController::class);
    Route::get('/presences/verify/{hashId}', [WorkerPresenceController::class, 'verify'])
        ->name('worker-presences.verify');
    Route::post('/presences/export', [WorkerPresenceController::class, 'exportExcel'])->name('worker-presences.export');
    Route::get('/presences/preview/{hashId}', [WorkerPresenceController::class, 'preview'])
        ->name('worker-presences.preview');

    // Worker Bonus
    Route::resource('worker-bonuses', WorkerBonusController::class);

    // Item Categories
    Route::resource('item-categories', ItemCategoryController::class);
    // Items
    Route::resource('items', ItemController::class);
    // Item Supliers
    Route::resource('item-suppliers', ItemSupplierController::class);
});

require __DIR__ . '/auth.php';
