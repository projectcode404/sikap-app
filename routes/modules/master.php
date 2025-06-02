<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\EmployeeController;
use App\Http\Controllers\Master\DivisionController;
use App\Http\Controllers\Master\PositionController;
use App\Http\Controllers\Master\WorkUnitController;
use App\Http\Controllers\Master\SupplierController;

Route::prefix('master')->name('master.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // Employees
        Route::get('employees/api', [EmployeeController::class, 'getEmployees'])->name('employees.api');
        Route::resource('employees', EmployeeController::class)->parameters(['employees' => 'employee']);

        // Divisions
        Route::get('divisions/api', [DivisionController::class, 'getDivisions'])->name('divisions.api');
        Route::resource('divisions', DivisionController::class)->parameters(['divisions' => 'division']);

        // Positions
        Route::get('positions/api', [PositionController::class, 'getPositions'])->name('positions.api');
        Route::resource('positions', PositionController::class)->parameters(['positions' => 'position']);

        // Work Units
        Route::get('work-units/api', [WorkUnitController::class, 'getWorkUnits'])->name('work-units.api');
        Route::get('/master/get-work-units', [WorkUnitController::class, 'getWorkUnitSelect'])->name('work-units.select');
        Route::resource('work-units', WorkUnitController::class)->parameters(['work-units' => 'work_unit']);

        // Suppliers
        Route::get('suppliers/api', [SupplierController::class, 'getSuppliers'])->name('suppliers.api');
        Route::resource('suppliers', SupplierController::class)->parameters(['suppliers' => 'supplier']);
    });
});