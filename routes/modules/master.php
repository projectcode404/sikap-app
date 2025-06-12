<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\EmployeeController;
use App\Http\Controllers\Master\DivisionController;
use App\Http\Controllers\Master\PositionController;
use App\Http\Controllers\Master\WorkUnitController;
use App\Http\Controllers\Master\SupplierController;

Route::prefix('master')->name('master.')->middleware(['auth'])->group(function () {

    // === Employees ===
    Route::get('employees/api', [EmployeeController::class, 'getEmployees'])->name('employees.api')->middleware('permission:employee_view');
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('permission:employee_view');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:employee_create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:employee_create');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:employee_edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:employee_edit');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:employee_delete');

    // === Divisions ===
    Route::get('divisions/api', [DivisionController::class, 'getDivisions'])->name('divisions.api')->middleware('permission:division_view');
    Route::get('divisions', [DivisionController::class, 'index'])->name('divisions.index')->middleware('permission:division_view');
    Route::get('divisions/create', [DivisionController::class, 'create'])->name('divisions.create')->middleware('permission:division_create');
    Route::post('divisions', [DivisionController::class, 'store'])->name('divisions.store')->middleware('permission:division_create');
    Route::get('divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit')->middleware('permission:division_edit');
    Route::put('divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update')->middleware('permission:division_edit');
    Route::delete('divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy')->middleware('permission:division_delete');

    // === Positions ===
    Route::get('positions/api', [PositionController::class, 'getPositions'])->name('positions.api')->middleware('permission:position_view');
    Route::get('positions', [PositionController::class, 'index'])->name('positions.index')->middleware('permission:position_view');
    Route::get('positions/create', [PositionController::class, 'create'])->name('positions.create')->middleware('permission:position_create');
    Route::post('positions', [PositionController::class, 'store'])->name('positions.store')->middleware('permission:position_create');
    Route::get('positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit')->middleware('permission:position_edit');
    Route::put('positions/{position}', [PositionController::class, 'update'])->name('positions.update')->middleware('permission:position_edit');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy')->middleware('permission:delete_position');

    // === Work Units ===
    Route::get('work-units/api', [WorkUnitController::class, 'getWorkUnits'])->name('work-units.api')->middleware('permission:work_unit_view');
    Route::get('get-work-units', [WorkUnitController::class, 'getWorkUnitSelect'])->name('work-units.select');
    Route::get('work-units', [WorkUnitController::class, 'index'])->name('work-units.index')->middleware('permission:work_unit_view');
    Route::get('work-units/create', [WorkUnitController::class, 'create'])->name('work-units.create')->middleware('permission:work_unit_create');
    Route::post('work-units', [WorkUnitController::class, 'store'])->name('work-units.store')->middleware('permission:work_unit_create');
    Route::get('work-units/{work_unit}/edit', [WorkUnitController::class, 'edit'])->name('work-units.edit')->middleware('permission:work_unit_edit');
    Route::put('work-units/{work_unit}', [WorkUnitController::class, 'update'])->name('work-units.update')->middleware('permission:work_unit_edit');
    Route::delete('work-units/{work_unit}', [WorkUnitController::class, 'destroy'])->name('work-units.destroy')->middleware('permission:work_unit_delete');

    // === Suppliers ===
    Route::get('suppliers/api', [SupplierController::class, 'getSuppliers'])->name('suppliers.api')->middleware('permission:supplier_view');
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index')->middleware('permission:supplier_view');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create')->middleware('permission:supplier_create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('permission:supplier_create');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('permission:supplier_edit');
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('permission:supplier_edit');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('permission:supplier_delete');

});