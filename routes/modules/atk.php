<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Atk\StockAtkController;
use App\Http\Controllers\Atk\PoAtkController;
use App\Http\Controllers\Atk\ReceiveAtkController;
use App\Http\Controllers\Atk\RequestAtkController;
use App\Http\Controllers\Atk\ReturnAtkController;

Route::prefix('atk')->name('atk.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // Stock ATK
        Route::get('stock/api', [StockAtkController::class, 'getStock'])->name('stock.api');
        Route::resource('stock', StockAtkController::class)->parameters(['stock' => 'stock']);

        // PO ATK
        Route::prefix('po-atk')->group(function () {
            Route::get('/', [PoAtkController::class, 'index'])->name('po-atk.index');
            Route::get('/create', [PoAtkController::class, 'create'])->name('po-atk.create');
            Route::get('/{id}', [PoAtkController::class, 'show'])->name('po-atk.show');
            Route::get('/{id}/edit', [PoAtkController::class, 'edit'])->name('po-atk.edit');
        
            // API endpoint untuk fetch data ke AG Grid
            Route::get('/api', [PoAtkController::class, 'getPoAtk'])->name('po-atk.api');
        });

        // Receive ATK
        Route::get('receive/api', [ReceiveAtkController::class, 'getReceive'])->name('receive.api');
        Route::resource('receive', ReceiveAtkController::class)->parameters(['receive' => 'receive']);

        // Request ATK
        Route::get('request/api', [RequestAtkController::class, 'getRequests'])->name('request.api');
        Route::get('request/upload', [RequestAtkController::class, 'showUploadForm'])->name('request.upload.form');
        Route::post('request/upload', [RequestAtkController::class, 'preview'])->name('request.upload.preview');
        Route::post('request/store-preview', [RequestAtkController::class, 'store'])->name('request.upload.store');
        Route::resource('request', RequestAtkController::class)->parameters(['request' => 'request']);

        // Request ATK Import
        Route::get('/import', [ImportRequestAtkController::class, 'showForm'])->name('atk.import.form');
        Route::post('/import', [ImportRequestAtkController::class, 'import'])->name('atk.import.process');
        Route::post('/import/commit', [ImportRequestAtkController::class, 'commit'])->name('atk.import.commit');

        // Return ATK
        Route::get('return/api', [ReturnAtkController::class, 'getReturns'])->name('return.api');
        Route::resource('return', ReturnAtkController::class)->parameters(['return' => 'return']);
    });
    
});