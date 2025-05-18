<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Atk\ItemController;
use App\Http\Controllers\Atk\PurchaseOrderController;
// use App\Http\Controllers\Atk\ReceiveController;
// use App\Http\Controllers\Atk\RequestController;
// use App\Http\Controllers\Atk\ReturnController;

Route::prefix('atk')->name('atk.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // ATK Items
        Route::get('items/api', [ItemController::class, 'getItems'])->name('items.api');
        Route::resource('items', ItemController::class)->parameters(['items' => 'item']);

        // PO ATK
        Route::get('purchase-orders/api', [PurchaseOrderController::class, 'getPurchaseOrders'])->name('purchase-orders.api');
        Route::get('purchase-orders/atk-items', [PurchaseOrderController::class, 'getAtkItems'])->name('get-atk-items');
        Route::get('purchase-orders/suppliers', [SupplierController::class, 'getSuppliers'])->name('get-suppliers');
        Route::resource('purchase-orders', PurchaseOrderController::class)->parameters(['purchase-orders' => 'purchaseOrder']);

        // // Receive ATK
        // Route::get('receive/api', [ReceiveAtkController::class, 'getReceive'])->name('receive.api');
        // Route::resource('receive', ReceiveAtkController::class)->parameters(['receive' => 'receive']);

        // // Request ATK
        // Route::get('request/api', [RequestAtkController::class, 'getRequests'])->name('request.api');
        // Route::get('request/upload', [RequestAtkController::class, 'showUploadForm'])->name('request.upload.form');
        // Route::post('request/upload', [RequestAtkController::class, 'preview'])->name('request.upload.preview');
        // Route::post('request/store-preview', [RequestAtkController::class, 'store'])->name('request.upload.store');
        // Route::resource('request', RequestAtkController::class)->parameters(['request' => 'request']);

        // // Request ATK Import
        // Route::get('/import', [ImportRequestAtkController::class, 'showForm'])->name('atk.import.form');
        // Route::post('/import', [ImportRequestAtkController::class, 'import'])->name('atk.import.process');
        // Route::post('/import/commit', [ImportRequestAtkController::class, 'commit'])->name('atk.import.commit');

        // // Return ATK
        // Route::get('return/api', [ReturnAtkController::class, 'getReturns'])->name('return.api');
        // Route::resource('return', ReturnAtkController::class)->parameters(['return' => 'return']);
    });
    
});