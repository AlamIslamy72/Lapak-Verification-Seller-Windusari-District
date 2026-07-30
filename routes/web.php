<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MfaController;
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

    // Route setup & konfirmasi MFA (tidak perlu middleware 'mfa' karena ini proses setup-nya sendiri)
    Route::get('/mfa/setup', [MfaController::class, 'setup'])->name('mfa.setup');
    Route::post('/mfa/confirm', [MfaController::class, 'confirm'])->name('mfa.confirm');

    // Route verifikasi MFA saat login
    Route::get('/mfa/verify', [MfaController::class, 'showVerifyForm'])->name('mfa.verify');
    Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify.submit');

    Route::get('/desa/dashboard', [DashboardController::class, 'desa'])
        ->middleware(['role:admin_desa', 'mfa']);
    Route::get('/shared/dashboard', [DashboardController::class, 'shared'])
        ->middleware(['role:admin_desa,admin_kecamatan', 'mfa']);
    Route::get('/kecamatan/dashboard', [DashboardController::class, 'kecamatan'])
        ->middleware(['role:admin_kecamatan', 'mfa']);
});

require __DIR__ . '/auth.php';
