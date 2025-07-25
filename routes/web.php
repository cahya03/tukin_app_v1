<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TukinController;

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

Route::resource('/headers', HeaderController::class)
    ->middleware(['auth', 'verified']);
require __DIR__ . '/auth.php';

// Routes untuk admin (pastikan sudah ada middleware admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
    Route::get('/activity-logs/export/csv', [ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
    Route::delete('/activity-logs/cleanup', [ActivityLogController::class, 'cleanup'])->name('admin.activity-logs.cleanup');
    Route::resource('/users', UsersController::class)->only(['index', 'show', 'edit','create','destroy','store','put','update'])
        ->names([
            'index' => 'admin.users.index',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'create' => 'admin.users.create',
            'destroy' => 'admin.users.destroy',
            'store' => 'admin.users.store',
            'put' => 'admin.users.put',
            'update' => 'admin.users.update',
        ]);
});

// Routes untuk user melihat aktivitas sendiri
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/activity-logs', [ActivityLogController::class, 'userLogs'])->name('profile.activity-logs');
});