<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GUEST routes
|--------------------------------------------------------------------------
*/
Route::get('/', [GuestController::class, 'landing'])->name('home');
Route::get('/deteksi', [GuestController::class, 'detection'])->name('guest.detection');
Route::post('/detect', [DetectionController::class, 'detect'])->name('detect');

/*
|--------------------------------------------------------------------------
| LOCALE switcher (publik, semua user bisa switch bahasa)
|--------------------------------------------------------------------------
*/
Route::post('/locale/{lang}', [\App\Http\Controllers\LocaleController::class, 'switch'])
    ->name('locale.switch');

/*
|--------------------------------------------------------------------------
| ADMIN routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
        Route::get('register', [RegisterController::class, 'show'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);
    });

    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
        Route::get('deteksi', [PageController::class, 'detection'])->name('detection');
        Route::get('pembelajaran', [PageController::class, 'learning'])->name('learning');
        Route::get('laporan', [PageController::class, 'reports'])->name('reports');
        Route::get('laporan/export/pdf', [PageController::class, 'reportsExportPdf'])->name('reports.export.pdf');
        Route::get('laporan/export/excel', [PageController::class, 'reportsExportExcel'])->name('reports.export.excel');

        // === Riwayat Deteksi (sebelumnya /admin/dataset) ===
        Route::get('riwayat', [PageController::class, 'history'])->name('history');
        Route::get('riwayat/{detection}', [PageController::class, 'historyDetail'])->name('history.detail');
        Route::delete('riwayat/{detection}', [PageController::class, 'historyDestroy'])->name('history.destroy');
        Route::post('riwayat/destroy-batch', [PageController::class, 'historyDestroyBatch'])->name('history.destroy-batch');

        // Backward compat: redirect URL lama
        Route::get('dataset', fn () => redirect()->route('admin.history', request()->query()))->name('dataset');
        Route::get('dataset/riwayat/{detection}', fn ($detection) => redirect()->route('admin.history.detail', $detection))->name('dataset.detail');

        // ML service health check
        Route::get('health/ml', [\App\Http\Controllers\Admin\HealthCheckController::class, 'mlService'])->name('health.ml');

        // Settings
        Route::get('settings', [SettingsController::class, 'show'])->name('settings');
        Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::delete('settings/avatar', [SettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');

        // User management
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});
