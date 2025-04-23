<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
// use App\Http\Controllers\User\RoleController;
// use App\Http\Controllers\User\PermissionController;

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // Users
        Route::get('users/api', [UserController::class, 'getUsers'])->name('users.api');
        Route::get('users/available-employees', [UserController::class, 'getAvailableEmployees'])->name('available.employees');
        Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::resource('users', UserController::class)->parameters(['users' => 'user']);
    });

    // // Roles
    // Route::get('roles/api', [RoleController::class, 'getRoles'])->name('roles.api');
    // Route::resource('roles', RoleController::class)->parameters(['roles' => 'role']);

    // // Permissions
    // Route::get('permissions/api', [PermissionController::class, 'getPermissions'])->name('permissions.api');
    // Route::resource('permissions', PermissionController::class)->parameters(['permissions' => 'permission']);

});