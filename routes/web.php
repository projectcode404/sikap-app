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
    Route::get('/getUsers', [UserController::class, 'getUsers'])->name('users.apiweb');
    //Employee
    Route::resource('employees', EmployeeController::class);

});

Route::get('/session-check', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user()
    ]);
});

require __DIR__.'/auth.php';
