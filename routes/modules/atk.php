<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Atk\ItemController;
use App\Http\Controllers\Atk\PurchaseOrderController;
use App\Http\Controllers\Atk\ReceiveController;
use App\Http\Controllers\Atk\AdjustmentController;
use App\Http\Controllers\Atk\OutRequestController;

Route::prefix('atk')->name('atk.')->middleware(['auth'])->group(function () {

    // === ATK Items ===
    Route::get('items/api', [ItemController::class, 'getItems'])->name('items.api')->middleware('permission:atk_item_view');
    Route::get('items/atk-items', [ItemController::class, 'getAtkItems'])->name('get-atk-items');
    Route::get('items', [ItemController::class, 'index'])->name('items.index')->middleware('permission:atk_item_view');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create')->middleware('permission:atk_item_create');
    Route::post('items', [ItemController::class, 'store'])->name('items.store')->middleware('permission:atk_item_create');
    Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit')->middleware('permission:atk_item_edit');
    Route::patch('items/{item}', [ItemController::class, 'update'])->name('items.update')->middleware('permission:atk_item_edit');
    Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('permission:atk_item_delete');

    // === PO ATK ===
    Route::get('purchase-orders/api', [PurchaseOrderController::class, 'getPurchaseOrders'])->name('purchase-orders.api')->middleware('permission:atk_purchase_order_view');
    Route::get('purchase-orders/suppliers', [SupplierController::class, 'getSuppliers'])->name('get-suppliers');
    Route::post('purchase-orders/submit-gr', [PurchaseOrderController::class, 'submitGr'])->name('purchase-orders.gr')->middleware('permission:atk_purchase_order_create');
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index')->middleware('permission:atk_purchase_order_view');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create')->middleware('permission:atk_purchase_order_create');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store')->middleware('permission:atk_purchase_order_create');
    Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit')->middleware('permission:atk_purchase_order_edit');
    Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show')->middleware('permission:atk_purchase_order_view');
    Route::patch('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update')->middleware('permission:atk_purchase_order_edit');
    Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy')->middleware('permission:atk_purchase_order_delete');

    // === Receives ===
    Route::get('receives/api', [ReceiveController::class, 'getReceives'])->name('receives.api')->middleware('permission:atk_receive_view');
    Route::get('receives', [ReceiveController::class, 'index'])->name('receives.index')->middleware('permission:atk_receive_view');
    Route::get('receives/{receive}', [ReceiveController::class, 'show'])->name('receive.show')->middleware('permission:atk_receive_view');
    Route::get('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'create'])->name('po-receive.create')->middleware('permission:atk_receive_create');
    Route::post('purchase-orders/{purchaseOrder}/receive', [ReceiveController::class, 'store'])->name('po-receive.store')->middleware('permission:atk_receive_create');

    // === Adjustments ===
    Route::get('adjustments/api', [AdjustmentController::class, 'getAdjustments'])->name('adjustments.api')->middleware('permission:atk_stock_adjustment_view');
    Route::get('adjustments', [AdjustmentController::class, 'index'])->name('adjustments.index')->middleware('permission:atk_stock_adjustment_view');
    Route::get('adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create')->middleware('permission:atk_stock_adjustment_create');
    Route::post('adjustments/store', [AdjustmentController::class, 'store'])->name('adjustments.store')->middleware('permission:atk_stock_adjustment_create');
    Route::get('adjustments/{adjustment}', [AdjustmentController::class, 'show'])->name('adjustments.show')->middleware('permission:atk_stock_adjustment_view');
    Route::get('adjustments/{adjustment}/edit', [AdjustmentController::class, 'edit'])->name('adjustments.edit')->middleware('permission:atk_stock_adjustment_edit');
    Route::patch('adjustments/{adjustment}', [AdjustmentController::class, 'update'])->name('adjustments.update')->middleware('permission:atk_stock_adjustment_edit');

    // === Out Requests ===
    Route::get('out-requests/api', [OutRequestController::class, 'getOutRequests'])->name('out-requests.api')->middleware('permission:atk_out_request_view');
    Route::get('out-requests', [OutRequestController::class, 'index'])->name('out-requests.index')->middleware('permission:atk_out_request_view');
    Route::get('out-requests/create', [OutRequestController::class, 'create'])->name('out-requests.create')->middleware('permission:atk_out_request_create');
    Route::post('out-requests', [OutRequestController::class, 'store'])->name('out-requests.store')->middleware('permission:atk_out_request_create');
    Route::get('out-requests/{outRequest}', [OutRequestController::class, 'show'])->name('out-requests.show')->middleware('permission:atk_out_request_view');
    Route::get('out-requests/{outRequest}/edit', [OutRequestController::class, 'edit'])->name('out-requests.edit')->middleware('permission:atk_out_request_edit');
    Route::patch('out-requests/{outRequest}', [OutRequestController::class, 'update'])->name('out-requests.update')->middleware('permission:atk_out_request_edit');
    Route::delete('out-requests/{outRequest}', [OutRequestController::class, 'destroy'])->name('out-requests.destroy')->middleware('permission:atk_out_request_delete');
    Route::get('out-requests/{outRequest}/review', [OutRequestController::class, 'review'])->name('out-requests.review')->middleware('permission:atk_out_request_approve');
    Route::get('out-requests/{outRequest}/print', [OutRequestController::class, 'print'])->name('out-requests.print')->middleware('permission:atk_out_request_print');
    Route::patch('out-requests/{outRequest}/action', [OutRequestController::class, 'handleAction'])->name('out-requests.action')->middleware('permission:atk_out_request_approve');
    
});