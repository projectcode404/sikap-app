<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Atk\StockController;
use App\Http\Controllers\Atk\PoController;
use App\Http\Controllers\Atk\ReceiveController;
use App\Http\Controllers\Atk\RequestController;
use App\Http\Controllers\Atk\ReturnController;

Route::prefix('atk')->name('atk.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // Stock ATK
        Route::get('stock/api', [StockController::class, 'getStock'])->name('stock.api');
        Route::resource('stock', StockController::class)->parameters(['stock' => 'stock']);

        // PO ATK
        Route::prefix('po-atk')->group(function () {
            Route::get('/', [PoAtkController::class, 'index'])->name('po-atk.index');
            Route::get('/create', [PoAtkController::class, 'create'])->name('po-atk.create');
            Route::get('/{id}', [PoAtkController::class, 'show'])->name('po-atk.show');
            Route::get('/{id}/edit', [PoAtkController::class, 'edit'])->name('po-atk.edit');
        
            // API endpoint untuk fetch data ke AG Grid
            Route::get('/api', [PoAtkController::class, 'api'])->name('po-atk.api');
        });

        // Receive ATK
        Route::get('receive/api', [ReceiveController::class, 'getReceive'])->name('receive.api');
        Route::resource('receive', ReceiveController::class)->parameters(['receive' => 'receive']);

        // Request ATK
        Route::get('request/api', [RequestController::class, 'getRequests'])->name('request.api');
        Route::get('request/upload', [RequestController::class, 'showUploadForm'])->name('request.upload.form');
        Route::post('request/upload', [RequestController::class, 'preview'])->name('request.upload.preview');
        Route::post('request/store-preview', [RequestController::class, 'store'])->name('request.upload.store');
        Route::resource('request', RequestController::class)->parameters(['request' => 'request']);

        // Request ATK Import
        Route::get('/import', [ImportRequestController::class, 'showForm'])->name('atk.import.form');
        Route::post('/import', [ImportRequestController::class, 'import'])->name('atk.import.process');
        Route::post('/import/commit', [ImportRequestController::class, 'commit'])->name('atk.import.commit');

        // Return ATK
        Route::get('return/api', [ReturnController::class, 'getReturns'])->name('return.api');
        Route::resource('return', ReturnController::class)->parameters(['return' => 'return']);
    });
    
});