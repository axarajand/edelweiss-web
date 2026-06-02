<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GUEST routes
|--------------------------------------------------------------------------
*/
Route::get('/', [GuestController::class, 'landing'])->name('home');
Route::get('/deteksi', [GuestController::class, 'detection'])->name('guest.detection');
Route::get('/riset', [GuestController::class, 'research'])->name('guest.research');
Route::get('/partner', [GuestController::class, 'partners'])->name('guest.partners');
Route::get('/galeri', [GuestController::class, 'gallery'])->name('guest.gallery');
Route::post('/detect', [DetectionController::class, 'detect'])->name('detect');

/*
|--------------------------------------------------------------------------
| LOCALE switcher (publik)
|--------------------------------------------------------------------------
*/
Route::post('/locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');

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

        // Laporan (termasuk tab Kondisi & Sistem yang dipindah dari Belajar)
        Route::get('laporan', [PageController::class, 'reports'])->name('reports');
        Route::get('laporan/export/pdf', [PageController::class, 'reportsExportPdf'])->name('reports.export.pdf');
        Route::get('laporan/export/excel', [PageController::class, 'reportsExportExcel'])->name('reports.export.excel');

        // Redirect backward compat untuk learning
        Route::get('pembelajaran', fn () => redirect()->route('admin.reports', ['tab' => 'kondisi']))->name('learning');

        // === Riwayat Deteksi ===
        Route::get('riwayat', [PageController::class, 'history'])->name('history');
        Route::get('riwayat/{detection}', [PageController::class, 'historyDetail'])->name('history.detail');
        Route::delete('riwayat/{detection}', [PageController::class, 'historyDestroy'])->name('history.destroy');
        Route::post('riwayat/destroy-batch', [PageController::class, 'historyDestroyBatch'])->name('history.destroy-batch');

        // Backward compat dataset
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

        // ===== KONTEN (R&D / Partner / Galeri) =====
        Route::get('konten', [ContentController::class, 'index'])->name('content.index');

        // Researcher CRUD (Tim Peneliti R&D)
        Route::post('konten/peneliti', [ContentController::class, 'storeResearcher'])->name('content.researcher.store');
        Route::put('konten/peneliti/{researcher}', [ContentController::class, 'updateResearcher'])->name('content.researcher.update');
        Route::delete('konten/peneliti/{researcher}', [ContentController::class, 'destroyResearcher'])->name('content.researcher.destroy');

        // Partner CRUD
        Route::post('konten/partner', [ContentController::class, 'storePartner'])->name('content.partner.store');
        Route::put('konten/partner/{partner}', [ContentController::class, 'updatePartner'])->name('content.partner.update');
        Route::delete('konten/partner/{partner}', [ContentController::class, 'destroyPartner'])->name('content.partner.destroy');

        // Gallery CRUD
        Route::post('konten/galeri', [ContentController::class, 'storeGallery'])->name('content.gallery.store');
        Route::put('konten/galeri/{gallery}', [ContentController::class, 'updateGallery'])->name('content.gallery.update');
        Route::delete('konten/galeri/{gallery}', [ContentController::class, 'destroyGallery'])->name('content.gallery.destroy');
    });
});
