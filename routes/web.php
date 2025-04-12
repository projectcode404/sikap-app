<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\WorkUnitController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Users
    Route::prefix('users')->group(function () {
        Route::get('/api', [UserController::class, 'getUsers'])->name('users.api');
        Route::resource('', UserController::class);
    });

    //Employees
    Route::prefix('employees')->group(function () {
        Route::get('/api', [EmployeeController::class, 'getEmployees'])->name('employees.api');
        Route::resource('', EmployeeController::class);
    });

    //Divisions
    Route::prefix('divisions')->group(function () {
        Route::get('/api', [DivisionController::class, 'getDivisions'])->name('divisions.api');
        Route::resource('', DivisionController::class);
    });

    //Positions
    Route::prefix('positions')->group(function () {
        Route::get('/api', [PositionController::class, 'getPositions'])->name('positions.api');
        Route::resource('', PositionController::class);
    });

    //WorkUnits
    Route::prefix('work-units')->group(function () {
        Route::get('/api', [WorkUnitController::class, 'getWorkUnits'])->name('work-units.api');
        Route::resource('', WorkUnitController::class);
    });

});

Route::get('/session-check', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user()
    ]);
});

require __DIR__.'/auth.php';
