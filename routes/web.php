<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/locale/{lang}', function ($lang) {
    if (! in_array($lang, ['en', 'id'])) {
        abort(400);
    }

    session(['locale' => $lang]);
    return back();
})->name('set-locale');

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/modules/master.php';
require __DIR__.'/modules/atk.php';
require __DIR__.'/modules/user.php';
require __DIR__.'/auth.php';

// require __DIR__.'/modules/vehicle.php';

// Route::get('/debug-role', function () {
//     $user = auth()->user();
//     return [
//         'user_id' => $user->id,
//         'roles' => $user->getRoleNames(), // Spatie
//         'has_admin_role' => $user->hasRole('admin'),
//     ];
// });
