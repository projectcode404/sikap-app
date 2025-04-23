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
        Route::get('po/api', [PoController::class, 'getPo'])->name('po.api');
        Route::resource('po', PoController::class)->parameters(['po' => 'po']);

        // Receive ATK
        Route::get('receive/api', [ReceiveController::class, 'getReceive'])->name('receive.api');
        Route::resource('receive', ReceiveController::class)->parameters(['receive' => 'receive']);

        // Request ATK
        Route::get('request/api', [RequestController::class, 'getRequests'])->name('request.api');
        Route::get('request/upload', [RequestController::class, 'showUploadForm'])->name('request.upload.form');
        Route::post('request/upload', [RequestController::class, 'preview'])->name('request.upload.preview');
        Route::post('request/store-preview', [RequestController::class, 'store'])->name('request.upload.store');
        Route::resource('request', RequestController::class)->parameters(['request' => 'request']);

        // Return ATK
        Route::get('return/api', [ReturnController::class, 'getReturns'])->name('return.api');
        Route::resource('return', ReturnController::class)->parameters(['return' => 'return']);
    });
    
});