<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    //Users
    Route::resource('users', UserController::class);
    
    //Employee
    Route::resource('employees', EmployeeController::class);
});

require __DIR__.'/auth.php';
