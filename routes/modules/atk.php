<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Atk\ItemController;
use App\Http\Controllers\Atk\PurchaseOrderController;
use App\Http\Controllers\Atk\ReceiveController;

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

        // PO Receive
        Route::get('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'create'])->name('po-receive.create');
        Route::post('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'store'])->name('po-receive.store');
    });
    
});