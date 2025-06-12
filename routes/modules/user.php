<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RolePermissionController;

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        // Users
        Route::get('users/api', [UserController::class, 'getUsers'])->name('users.api');
        Route::get('users/available-employees', [UserController::class, 'getAvailableEmployees'])->name('available.employees');
        Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::resource('users', UserController::class)->parameters(['users' => 'user']);

        // Roles
        Route::get('roles/api', [RoleController::class, 'getRoles'])->name('roles.api');
        Route::get('permissions/api', [PermissionController::class, 'getPermissions'])->name('permissions.api');
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::get('roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
        Route::put('roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    });
});