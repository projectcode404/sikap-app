<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Atk\ItemController;
use App\Http\Controllers\Atk\PurchaseOrderController;
use App\Http\Controllers\Atk\ReceiveController;
use App\Http\Controllers\Atk\AdjustmentController;

Route::prefix('atk')->name('atk.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // ATK Items
        Route::get('items/api', [ItemController::class, 'getItems'])->name('items.api');
        Route::get('items/atk-items', [ItemController::class, 'getAtkItems'])->name('get-atk-items');
        Route::resource('items', ItemController::class)->parameters(['items' => 'item']);

        // PO ATK
        Route::get('purchase-orders/api', [PurchaseOrderController::class, 'getPurchaseOrders'])->name('purchase-orders.api');
        // Route::get('purchase-orders/atk-items', [PurchaseOrderController::class, 'getAtkItems'])->name('get-atk-items');
        Route::get('purchase-orders/suppliers', [SupplierController::class, 'getSuppliers'])->name('get-suppliers');
        Route::post('purchase-orders/gr', [PurchaseOrderController::class, 'submitGr'])->name('purchase-orders.gr');
        Route::resource('purchase-orders', PurchaseOrderController::class)->parameters(['purchase-orders' => 'purchaseOrder']);

        // Receive
        Route::get('receives/api', [ReceiveController::class, 'getReceives'])->name('receives.api');
        Route::get('receives', [ReceiveController::class, 'index'])->name('receives.index');
        Route::get('receives/{receive}', [ReceiveController::class, 'show'])->name('receive.show');

        // PO Receive
        Route::get('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'create'])->name('po-receive.create');
        Route::post('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'store'])->name('po-receive.store');

        // Adjustments
        Route::get('adjustments/api', [AdjustmentController::class, 'getAdjustments'])->name('adjustments.api');
        Route::get('adjustments', [AdjustmentController::class, 'index'])->name('adjustments.index');
        Route::get('adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create');
        Route::post('adjustments', [AdjustmentController::class, 'store'])->name('adjustments.store');
        Route::get('adjustments/{adjustment}', [AdjustmentController::class, 'show'])->name('adjustments.show');
    });
    
});