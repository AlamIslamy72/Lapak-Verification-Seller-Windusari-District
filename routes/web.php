<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/desa/dashboard', [DashboardController::class, 'desa'])
        ->middleware('role:admin_desa');
    Route::get('/shared/dashboard', [DashboardController::class, 'shared'])
        ->middleware('role:admin_desa,admin_kecamatan');
    Route::get('/kecamatan/dashboard', [DashboardController::class, 'kecamatan'])
        ->middleware('role:admin_kecamatan');
});

require __DIR__ . '/auth.php';
