<?php

use App\Http\Controllers\DevelopmentPointController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemInController;
use App\Http\Controllers\ItemLogController;
use App\Http\Controllers\ItemOutController;
use App\Http\Controllers\ItemStockController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerBonusController;
use App\Http\Controllers\WorkerCategoryController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerPresenceClickController;
use App\Http\Controllers\WorkerPresenceController;
use App\Http\Controllers\WorkerPresenceScheduleController;
use App\Models\Item;
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
    // Worker Presence (Click)
    Route::get('worker-presences-click', [WorkerPresenceClickController::class, 'index'])->name('worker-presences-click.index');
    Route::post('worker-presences-click/save-all', [WorkerPresenceClickController::class, 'saveAll'])->name('worker-presences-click.save-all');
    Route::post('worker-presences-click/export', [WorkerPresenceClickController::class, 'exportExcel'])
        ->name('worker-presences-click.export');
    // Worker Bonus
    Route::resource('worker-bonuses', WorkerBonusController::class);

    // Item Categories
    Route::resource('item-categories', ItemCategoryController::class);
    // Items
    Route::post('/items/import', [ItemController::class, 'import'])->name('items.import');
    Route::resource('items', ItemController::class);
    // Item Supliers
    Route::resource('item-suppliers', ItemSupplierController::class);
    // Development Points
    Route::resource('development-points', DevelopmentPointController::class);
    // Item Stocks
    Route::get('/get-items-by-category/{id}', [ItemStockController::class, 'getByCategory'])
        ->name('items.byCategory');
    Route::get('/item-stocks/export', [ItemStockController::class, 'export'])->name('item-stocks.export');
    Route::resource('item-stocks', ItemStockController::class);
    // Incoming Items
    Route::get('/item-ins/export', [ItemInController::class, 'export'])->name('item-ins.export');
    Route::get('/get-items-by-category/{id}', [ItemInController::class, 'getItemsByCategory'])
        ->name('item-ins.get-items');
    Route::resource('item-ins', ItemInController::class);
    // Outgoing Items
    Route::get('/get-items-by-category/{id}', [ItemOutController::class, 'getItemsByCategory']);
    Route::resource('item-outs', ItemOutController::class);
    // Item Logs
    Route::get('/item-logs/export', [ItemLogController::class, 'export'])->name('item-logs.export');
    Route::resource('item-logs', ItemLogController::class);
});

require __DIR__ . '/auth.php';
