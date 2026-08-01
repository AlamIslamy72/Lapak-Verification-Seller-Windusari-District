<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PublicSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ================= PUBLIK (Pedagang) - Tanpa Login =================
Route::get('/daftar', [PublicSubmissionController::class, 'create'])->name('public.daftar');
Route::post('/daftar', [PublicSubmissionController::class, 'store'])->name('public.daftar.store');
Route::get('/daftar/sukses/{registrationNumber}', [PublicSubmissionController::class, 'success'])->name('public.daftar.success');

Route::get('/lacak', [PublicSubmissionController::class, 'trackForm'])->name('public.lacak');
Route::post('/lacak', [PublicSubmissionController::class, 'trackResult'])->name('public.lacak.result');

// ================= PETUGAS (Login Required) =================
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user->role) {
        abort(403, 'Akun Anda belum memiliki role. Hubungi admin.');
    }

    return match ($user->role->name) {
        'admin_desa' => redirect()->route('desa.dashboard'),
        'admin_kecamatan' => redirect()->route('kecamatan.dashboard'),
        default => abort(403, 'Role tidak dikenali.'),
    };
})->middleware(['auth', 'verified', 'mfa'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mfa/setup', [MfaController::class, 'setup'])->name('mfa.setup');
    Route::post('/mfa/confirm', [MfaController::class, 'confirm'])->name('mfa.confirm');
    Route::get('/mfa/verify', [MfaController::class, 'showVerifyForm'])->name('mfa.verify');
    Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify.submit');

    Route::get('/desa/dashboard', [DashboardController::class, 'desa'])
        ->middleware(['role:admin_desa', 'mfa'])->name('desa.dashboard');
    Route::get('/shared/dashboard', [DashboardController::class, 'shared'])
        ->middleware(['role:admin_desa,admin_kecamatan', 'mfa'])->name('shared.dashboard');
    Route::get('/kecamatan/dashboard', [DashboardController::class, 'kecamatan'])
        ->middleware(['role:admin_kecamatan', 'mfa'])->name('kecamatan.dashboard');

    // Detail & Aksi Submission (bisa diakses admin_desa & admin_kecamatan, dibatasi logic controller)
    Route::middleware('mfa')->group(function () {
        Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{submission}/category', [SubmissionController::class, 'assignCategory'])->name('submissions.category');
        Route::post('/submissions/{submission}/survey-photo', [SubmissionController::class, 'uploadSurveyPhoto'])->name('submissions.survey-photo');
        Route::post('/submissions/{submission}/village-notes', [SubmissionController::class, 'saveVillageNotes'])->name('submissions.village-notes');
        Route::post('/submissions/{submission}/district-notes', [SubmissionController::class, 'saveDistrictNotes'])->name('submissions.district-notes');
        Route::post('/submissions/{submission}/approve-village', [SubmissionController::class, 'approveVillage'])->name('submissions.approve-village');
        Route::post('/submissions/{submission}/reject-village', [SubmissionController::class, 'rejectVillage'])->name('submissions.reject-village');
        Route::post('/submissions/{submission}/approve-district', [SubmissionController::class, 'approveDistrict'])->name('submissions.approve-district');
        Route::post('/submissions/{submission}/reject-district', [SubmissionController::class, 'rejectDistrict'])->name('submissions.reject-district');
    });
});

require __DIR__.'/auth.php';
