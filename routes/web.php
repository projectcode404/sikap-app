<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\DivisionController;
use App\Http\Controllers\Employee\PositionController;
use App\Http\Controllers\Employee\WorkUnitController;
use App\Http\Controllers\Atk\StockController;
use App\Http\Controllers\Atk\RequestController;
use App\Http\Controllers\Atk\PoController;
use App\Http\Controllers\Atk\ReceiveController;
use App\Http\Controllers\Atk\ReturnController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Users
    Route::prefix('user')->name('user.')->group(function () {

        // Users
        Route::prefix('data')->name('users.')->group(function () {
            Route::get('/api', [UserController::class, 'getUsers'])->name('api');
            Route::resource('/', UserController::class)->parameters(['' => 'user']);
        });
        
    });

    // Employee
    Route::prefix('employee')->name('employee.')->group(function () {
    
        // Employees
        Route::prefix('data')->name('employees.')->group(function () {
            Route::get('/api', [EmployeeController::class, 'getEmployees'])->name('api');
            Route::resource('/', EmployeeController::class)->parameters(['' => 'employee']);
        });
    
        // Divisions
        Route::prefix('divisions')->name('divisions.')->group(function () {
            Route::get('/api', [DivisionController::class, 'getDivisions'])->name('api');
            Route::resource('/', DivisionController::class)->parameters(['' => 'division']);
        });
    
        // Positions
        Route::prefix('positions')->name('positions.')->group(function () {
            Route::get('/api', [PositionController::class, 'getPositions'])->name('api');
            Route::resource('/', PositionController::class)->parameters(['' => 'position']);
        });
    
        // Work Units
        Route::prefix('work-units')->name('work-units.')->group(function () {
            Route::get('/api', [WorkUnitController::class, 'getWorkUnits'])->name('api');
            Route::resource('/', WorkUnitController::class)->parameters(['' => 'work_unit']);
        });
    
    });

    // ATK
Route::prefix('atk')->name('atk.')->group(function () {

    // 📦 Stock ATK
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/api', [StockController::class, 'getStock'])->name('api');
        Route::resource('/', StockController::class)->parameters(['' => 'stock']);
    });

    // 📄 Request ATK
    Route::prefix('request')->name('request.')->group(function () {
        Route::get('/form', [RequestController::class, 'createForm'])->name('form');
        Route::post('/store', [RequestController::class, 'storeFromForm'])->name('store');

        Route::get('/upload', [RequestController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload-preview', [RequestController::class, 'preview'])->name('upload.preview');
        Route::post('/upload-submit', [RequestController::class, 'store'])->name('upload.submit');
    });

    // 📑 PO ATK
    Route::prefix('po')->name('po.')->group(function () {
        Route::get('/', [PoController::class, 'index'])->name('index');
        Route::get('/create', [PoController::class, 'create'])->name('create');
        Route::post('/store', [PoController::class, 'store'])->name('store');
    });

    // 📥 Receive ATK
    Route::prefix('receive')->name('receive.')->group(function () {
        Route::get('/', [ReceiveController::class, 'index'])->name('index');
        Route::get('/create/{po_id}', [ReceiveController::class, 'create'])->name('create');
        Route::post('/store', [ReceiveController::class, 'store'])->name('store');
    });

    // 🔁 Return ATK
    Route::prefix('return')->name('return.')->group(function () {
        Route::get('/form', [ReturnController::class, 'form'])->name('form');
        Route::post('/store', [ReturnController::class, 'store'])->name('store');
    });

});

});

// Route::get('/debug-role', function () {
//     $user = auth()->user();
//     return [
//         'user_id' => $user->id,
//         'roles' => $user->getRoleNames(), // Spatie
//         'has_admin_role' => $user->hasRole('admin'),
//     ];
// });

require __DIR__.'/auth.php';
