<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tukin', [TukinController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tukin');

Route::get('/tukin/create', [TukinController::class, 'create_tukin'])
    ->middleware(['auth', 'verified'])
    ->name('tukin-create');

Route::post('/tukin/create/import_tni', [TukinController::class, 'import_excel_tni'])
    ->middleware(['auth', 'verified'])
    ->name('tukin.import_tni');

Route::resource('/headers', HeaderController::class)
    ->middleware(['auth', 'verified']);
require __DIR__ . '/auth.php';

// Routes untuk admin (pastikan sudah ada middleware admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
    Route::get('/activity-logs/export/csv', [ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
    Route::delete('/activity-logs/cleanup', [ActivityLogController::class, 'cleanup'])->name('admin.activity-logs.cleanup');
    Route::get('/dashboard/logs', [ActivityLogController::class, 'dashboard'])->name('admin.dashboard.logs');
});

// Routes untuk user melihat aktivitas sendiri
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/activity-logs', [ActivityLogController::class, 'userLogs'])->name('profile.activity-logs');
});

// Atau bisa juga menggunakan resource route
// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
// });